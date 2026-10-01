<?php

/*
 * Writes lib/Model, lib/Request and lib/Enum from spec/openapi.yaml: the models, the request bodies, the query and header parameters and the enums.
 * The services, the pages and the rest of lib/ are written by hand.
 * Run it after every change of the spec, then run php-cs-fixer and the tests.
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

const GROUPS = ['Payments', 'Customers', 'Stores', 'PaymentGateways', 'Receipts', 'Terminals', 'Reports'];

const RENAMES = [
    'CompanyAddressForExternalApi' => 'CompanyAddress',
    'PaymentGateways.GetPaymentGatewaysResponse' => 'PaymentGatewayList',
];

// Schemas that are not classes of their own: Id is a string, Metadata a map, the problem details are read by the exceptions.
const SKIP = ['Id', 'Metadata', 'ProblemDetails', 'ValidationProblemDetails', 'PaymentConflictProblemDetails', 'PaginationResponse'];

const HANDWRITTEN = ['PaginationResponse' => 'Pagination'];

const HANDWRITTEN_FILES = ['Model/Page.php', 'Model/Pagination.php', 'Request/RequestParams.php'];

// An enum declared inside a property has no name in the spec: a new one must be named here, and in tests/SchemaCoverageTest.php.
const INLINE_ENUMS = [
    'Company.packagingType' => 'PackagingType',
    'Customers.Packaging.type' => 'PackagingType',
    'CreatePaymentRequest.completionMode' => 'CompletionMode',
    'CreatePaymentRequest.exceedingAmountMode' => 'ExceedingAmountMode',
    'Stores.StoreStatus.status' => 'StoreStatusValue',
];

const INCLUDE_ENUMS = [
    'retrieve-company' => 'CompanyInclude',
    'retrieve-customer' => 'CustomerInclude',
    'retrieve-payment' => 'PaymentInclude',
    'list-payment-gateway' => 'PaymentGatewayInclude',
];

const METHODS = ['get', 'post', 'put', 'patch', 'delete'];

$root = \dirname(__DIR__);
$lib = $root . '/lib';
$spec = Yaml::parseFile($root . '/spec/openapi.yaml');
$schemas = $spec['components']['schemas'];

$classes = [];
$enums = [];

function pascal(string $value): string
{
    return strtoupper($value[0]) . substr($value, 1);
}

function className(string $schemaKey): string
{
    if (isset(RENAMES[$schemaKey])) {
        return RENAMES[$schemaKey];
    }
    if (isset(HANDWRITTEN[$schemaKey])) {
        return HANDWRITTEN[$schemaKey];
    }
    $segments = explode('.', $schemaKey);
    if (\in_array($segments[0], GROUPS, true)) {
        array_shift($segments);
    }

    return implode('', array_map('pascal', $segments));
}

function refName(string $ref): string
{
    return substr($ref, strrpos($ref, '/') + 1);
}

/**
 * @return array{0: string, 1: mixed} ['ref', schema key] or ['inline', schema]
 */
function unwrap(array $property): array
{
    if (isset($property['$ref'])) {
        return ['ref', refName($property['$ref'])];
    }
    if (isset($property['allOf'])) {
        if (1 !== \count($property['allOf'])) {
            throw new RuntimeException('An allOf with more than one schema is not supported.');
        }

        return ['ref', refName($property['allOf'][0]['$ref'])];
    }

    return ['inline', $property];
}

function addEnum(string $name, array $schema, ?string $description = null): void
{
    global $enums;
    $values = $schema['enum'];
    if (isset($enums[$name])) {
        if ($enums[$name]['values'] !== $values) {
            throw new RuntimeException("The enum {$name} is declared twice with different values.");
        }

        return;
    }

    $names = null;
    $descriptions = null;
    $xd = $schema['x-enum-descriptions'] ?? null;
    if ($xd && \is_string($xd[0])) {
        $names = array_map(static fn (string $d): string => explode(' ', trim(explode(':', $d, 2)[1]))[0], $xd);
        $descriptions = array_map(static fn (string $d): string => trim(explode(':', $d, 2)[1]), $xd);
    } elseif ($xd) {
        $byValue = [];
        foreach ($xd as $entry) {
            foreach ($entry as $value => $text) {
                $byValue[$value] = $text;
            }
        }
        $descriptions = array_map(static fn ($value): string => $byValue[$value], $values);
    }

    $enums[$name] = [
        'values' => $values,
        'description' => $description ?? ($schema['description'] ?? null),
        'kind' => 'integer' === ($schema['type'] ?? null) ? 'int' : 'string',
        'names' => $names,
        'descriptions' => $descriptions,
    ];
}

function addClass(string $name, string $schemaKey, array $schema): void
{
    global $classes;
    if (isset($classes[$name])) {
        return;
    }
    $classes[$name] = ['schemaKey' => $schemaKey, 'description' => $schema['description'] ?? null, 'properties' => $schema['properties'] ?? [], 'required' => $schema['required'] ?? []];
    foreach ($classes[$name]['properties'] as $jsonName => $property) {
        visitProperty($name, $schemaKey, (string) $jsonName, $property);
    }
}

function visitProperty(string $class, string $schemaKey, string $jsonName, array $property): void
{
    [$kind, $detail] = unwrap($property);
    if ('ref' === $kind) {
        visitRef($detail);

        return;
    }
    if (isset($detail['enum'])) {
        $key = $schemaKey . '.' . $jsonName;
        if (!isset(INLINE_ENUMS[$key])) {
            throw new RuntimeException("The inline enum {$key} needs a name in INLINE_ENUMS.");
        }
        addEnum(INLINE_ENUMS[$key], $detail);

        return;
    }
    if ('array' === ($detail['type'] ?? null)) {
        visitProperty($class, $schemaKey, $jsonName, $detail['items']);

        return;
    }
    if ('object' === ($detail['type'] ?? null) && isset($detail['properties'])) {
        addClass($class . pascal($jsonName), $schemaKey . '.' . $jsonName, $detail);
    }
}

function visitRef(string $schemaKey): void
{
    global $schemas;
    if (\in_array($schemaKey, SKIP, true)) {
        return;
    }
    $schema = $schemas[$schemaKey];
    if (isset($schema['enum'])) {
        addEnum(className($schemaKey), $schema);
    } else {
        addClass(className($schemaKey), $schemaKey, $schema);
    }
}

/**
 * @return iterable<array{string, array<string, mixed>}> [operationId, operation]
 */
function operations(): iterable
{
    global $spec;
    foreach ($spec['paths'] as $item) {
        foreach ($item as $method => $operation) {
            if (\in_array($method, METHODS, true)) {
                yield [$operation['operationId'], $operation];
            }
        }
    }
}

foreach (array_keys($schemas) as $key) {
    visitRef($key);
}

addClass('VolumesReport', 'VolumesReport', $spec['paths']['/v1/reports/volumes']['get']['responses'][200]['content']['application/json']['schema']);

foreach (operations() as [$operationId, $operation]) {
    foreach ($operation['parameters'] ?? [] as $parameter) {
        if ('include' === ($parameter['name'] ?? null)) {
            $items = $parameter['schema']['items'];
            if (isset($items['enum'])) {
                if (!isset(INCLUDE_ENUMS[$operationId])) {
                    throw new RuntimeException("The include enum of {$operationId} needs a name in INCLUDE_ENUMS.");
                }
                addEnum(INCLUDE_ENUMS[$operationId], $items, '');
            } else {
                visitRef(refName($items['$ref']));
            }
        }
    }
}

// A class used only by request bodies goes in Request; every other class goes in Model.
$requestRoots = [];
$responseRoots = ['VolumesReport'];
foreach (operations() as [$operationId, $operation]) {
    if (isset($operation['requestBody'])) {
        $requestRoots[] = className(refName($operation['requestBody']['content']['application/json']['schema']['$ref']));
    }
    foreach ($operation['responses'] as $code => $response) {
        $schema = $response['content']['application/json']['schema'] ?? null;
        if ('2' !== ((string) $code)[0] || null === $schema) {
            continue;
        }
        if (isset($schema['$ref'])) {
            $responseRoots[] = className(refName($schema['$ref']));
        } elseif (isset($schema['properties'])) {
            foreach ($schema['properties'] as $property) {
                [$kind, $detail] = unwrap($property);
                while ('inline' === $kind && 'array' === ($detail['type'] ?? null)) {
                    [$kind, $detail] = unwrap($detail['items']);
                }
                if ('ref' === $kind) {
                    $responseRoots[] = className($detail);
                }
            }
        }
    }
}

function children(string $class): array
{
    global $classes, $schemas;
    $out = [];
    foreach ($classes[$class]['properties'] as $jsonName => $property) {
        [$kind, $detail] = unwrap($property);
        while ('inline' === $kind && 'array' === ($detail['type'] ?? null)) {
            [$kind, $detail] = unwrap($detail['items']);
        }
        if ('ref' === $kind && !\in_array($detail, SKIP, true) && !isset($schemas[$detail]['enum'])) {
            $out[] = className($detail);
        } elseif ('inline' === $kind && 'object' === ($detail['type'] ?? null) && isset($detail['properties'])) {
            $out[] = $class . pascal((string) $jsonName);
        }
    }

    return array_values(array_filter($out, static fn (string $name): bool => isset($classes[$name])));
}

function reach(array $roots): array
{
    global $classes;
    $seen = [];
    $stack = array_values(array_filter($roots, static fn (string $name): bool => isset($classes[$name])));
    while ([] !== $stack) {
        $class = array_pop($stack);
        if (isset($seen[$class])) {
            continue;
        }
        $seen[$class] = true;
        array_push($stack, ...children($class));
    }

    return $seen;
}

$inRequest = reach($requestRoots);
$inResponse = reach($responseRoots);
$namespaces = [];
foreach (array_keys($classes) as $class) {
    if (!isset($inRequest[$class]) && !isset($inResponse[$class])) {
        throw new RuntimeException("The class {$class} is used by no operation.");
    }
    $namespaces[$class] = isset($inRequest[$class]) && !isset($inResponse[$class]) ? 'Request' : 'Model';
}

/**
 * @return array{php: string, doc: ?string, types: ?string, enum: ?string, itemEnum: ?string, class: ?string}
 */
function propertyType(string $class, string $schemaKey, string $jsonName, array $property): array
{
    global $schemas, $enums, $namespaces;
    $type = static fn (string $php, ?string $doc = null, ?string $types = null, ?string $enum = null, ?string $itemEnum = null, ?string $class = null): array => compact('php', 'doc', 'types', 'enum', 'itemEnum', 'class');

    [$kind, $detail] = unwrap($property);
    if ('ref' === $kind) {
        if ('Id' === $detail) {
            return $type('?string');
        }
        if ('Metadata' === $detail) {
            return $type('?array', 'null|array<string, string>', "'map'");
        }
        if (isset(HANDWRITTEN[$detail])) {
            $name = HANDWRITTEN[$detail];

            return $type('?' . $name, null, $name . '::class', null, null, $name);
        }
        if (isset($schemas[$detail]['enum'])) {
            $enum = className($detail);

            return $type('int' === $enums[$enum]['kind'] ? '?int' : '?string', null, null, $enum);
        }
        $name = className($detail);

        return $type('?' . $name, null, $name . '::class', null, null, $name);
    }
    if (isset($detail['enum'])) {
        return $type('?string', null, null, INLINE_ENUMS[$schemaKey . '.' . $jsonName]);
    }

    switch ($detail['type'] ?? null) {
        case 'array':
            $item = propertyType($class, $schemaKey, $jsonName, $detail['items']);
            if (null !== $item['class']) {
                return $type('?array', 'null|list<' . $item['class'] . '>', '[' . $item['class'] . '::class]', null, null, $item['class']);
            }

            return $type('?array', 'null|list<' . substr($item['php'], 1) . '>', null, null, $item['enum']);

        case 'object':
            if (isset($detail['properties'])) {
                $name = $class . pascal($jsonName);

                return $type('?' . $name, null, $name . '::class', null, null, $name);
            }

            return $type('?array', 'null|array<string, mixed>');

        case 'string':
            if ('date' === ($detail['format'] ?? null) && 'Request' === $namespaces[$class]) {
                return $type('?\DateTimeInterface', null, "'date'");
            }

            return $type('?string');

        case 'integer':
            return $type('?int');

        case 'number':
            return $type('?float');

        case 'boolean':
            return $type('?bool');
    }

    throw new RuntimeException("The property {$class}.{$jsonName} has a type the generator does not know.");
}

/**
 * Joins the lines of a description, keeping a line break only after a line that ends with a full stop and before a list item.
 *
 * @return list<string>
 */
function sentences(?string $text): array
{
    if (null === $text || '' === trim($text)) {
        return [];
    }
    $out = [];
    $current = '';
    foreach (explode("\n", trim($text)) as $line) {
        $line = trim($line);
        if ('' === $line) {
            continue;
        }
        if ('' !== $current && ('.' === substr($current, -1) || 0 === strpos($line, '- '))) {
            $out[] = $current;
            $current = $line;
        } elseif ('' !== $current) {
            $current .= ' ' . $line;
        } else {
            $current = $line;
        }
    }
    if ('' !== $current) {
        $out[] = $current;
    }

    return $out;
}

function docBlock(array $lines, string $indent = '    ', array $tags = []): string
{
    $lines = array_map(static fn (string $line): string => str_replace('*/', '*\/', $line), $lines);
    if ([] !== $lines && !\in_array(substr($lines[0], -1), ['.', '!', '?', ':'], true)) {
        $lines[0] .= '.';
    }
    if ([] !== $tags) {
        if ([] !== $lines) {
            $lines[] = '';
        }
        array_push($lines, ...$tags);
    }
    if ([] === $lines) {
        return '';
    }
    $out = $indent . "/**\n";
    foreach ($lines as $line) {
        $out .= rtrim($indent . ' * ' . $line) . "\n";
    }

    return $out . $indent . " */\n";
}

function constantName($value): string
{
    return strtoupper(preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', (string) $value));
}

function write(string $path, string $content): void
{
    if (!is_dir(\dirname($path))) {
        mkdir(\dirname($path), 0777, true);
    }
    file_put_contents($path, $content);
}

function fileHeader(string $namespace, array $uses): string
{
    $uses = array_unique($uses);
    sort($uses, SORT_STRING);
    $out = "<?php\n\nnamespace VoucherlyApi\\{$namespace};\n\n";
    foreach ($uses as $use) {
        $out .= "use {$use};\n";
    }

    return $out . ([] !== $uses ? "\n" : '');
}

$written = [];

foreach ($classes as $class => $definition) {
    $namespace = $namespaces[$class];
    $uses = ['VoucherlyApi\VoucherlyObject'];
    $properties = [];
    $types = [];
    foreach ($definition['properties'] as $jsonName => $property) {
        $jsonName = (string) $jsonName;
        $type = propertyType($class, $definition['schemaKey'], $jsonName, $property);
        // A response may leave any property out, so only a class used by requests alone can refuse null.
        if ('Request' === $namespace && false === ($property['nullable'] ?? null)) {
            $type['php'] = substr($type['php'], 1);
            $type['doc'] = null !== $type['doc'] ? substr($type['doc'], \strlen('null|')) : null;
        }
        $name = '$type' === $jsonName ? 'type' : $jsonName;
        [$kind, $detail] = unwrap($property);
        $lines = sentences($property['description'] ?? ('inline' === $kind ? ($detail['description'] ?? null) : null));
        if ('Request' === $namespace && \in_array($jsonName, $definition['required'], true)) {
            $lines[] = 'Required.';
        }
        if (null !== $type['enum']) {
            $lines[] = 'One of the {@see ' . $type['enum'] . '} constants.';
            $uses[] = 'VoucherlyApi\Enum\\' . $type['enum'];
        }
        if (null !== $type['itemEnum']) {
            $lines[] = 'Each item is one of the {@see ' . $type['itemEnum'] . '} constants.';
            $uses[] = 'VoucherlyApi\Enum\\' . $type['itemEnum'];
        }
        if (null !== $type['class'] && ($namespaces[$type['class']] ?? 'Model') !== $namespace) {
            $uses[] = 'VoucherlyApi\\' . ($namespaces[$type['class']] ?? 'Model') . '\\' . $type['class'];
        }
        $tags = [];
        if ($property['deprecated'] ?? false) {
            $tags[] = '@deprecated';
        }
        if (null !== $type['doc']) {
            $tags[] = '@var ' . $type['doc'];
        }
        $properties[] = docBlock($lines, '    ', $tags) . "    public {$type['php']} \${$name};\n";
        if (null !== $type['types']) {
            $types[] = "            '{$name}' => {$type['types']},";
        }
    }

    $body = implode("\n", $properties);
    if ([] !== $types) {
        $body .= "\n    protected static function types(): array\n    {\n        return [\n" . implode("\n", $types) . "\n        ];\n    }\n";
    }
    if (isset($definition['properties']['$type'])) {
        $body .= "\n    protected static function jsonNames(): array\n    {\n        return ['type' => '\$type'];\n    }\n";
    }

    $content = fileHeader($namespace, $uses) . docBlock(sentences($definition['description']), '') . "class {$class} extends VoucherlyObject\n{\n" . $body . "}\n";
    write("{$lib}/{$namespace}/{$class}.php", $content);
    $written[] = "{$namespace}/{$class}.php";
}

foreach ($enums as $name => $enum) {
    $constants = [];
    foreach ($enum['values'] as $i => $value) {
        $constant = constantName(null !== $enum['names'] ? $enum['names'][$i] : $value);
        $literal = 'int' === $enum['kind'] ? (string) $value : "'{$value}'";
        $lines = null !== $enum['descriptions'] ? sentences(rtrim($enum['descriptions'][$i]) . ('.' === substr(rtrim($enum['descriptions'][$i]), -1) ? '' : '.')) : [];
        $constants[] = docBlock($lines) . "    public const {$constant} = {$literal};\n";
    }
    $content = fileHeader('Enum', []) . docBlock(sentences($enum['description']), '') . "final class {$name}\n{\n" . implode("\n", $constants) . "}\n";
    write("{$lib}/Enum/{$name}.php", $content);
    $written[] = "Enum/{$name}.php";
}

$parameterRefs = $spec['components']['parameters'];

function parameterClassName(string $operationId): string
{
    return implode('', array_map('pascal', explode('-', $operationId))) . 'Params';
}

function parameterPropertyName(array $parameter): string
{
    if ('header' !== $parameter['in']) {
        return $parameter['name'];
    }
    $words = explode('-', str_replace('Voucherly-', '', $parameter['name']));

    return strtolower(array_shift($words)) . implode('', array_map(static fn (string $word): string => pascal(strtolower($word)), $words));
}

foreach (operations() as [$operationId, $operation]) {
    $parameters = [];
    foreach ($operation['parameters'] ?? [] as $parameter) {
        if (isset($parameter['$ref'])) {
            $parameter = $parameterRefs[refName($parameter['$ref'])];
        }
        if ('path' !== $parameter['in']) {
            $parameters[] = $parameter;
        }
    }
    if ([] === $parameters) {
        continue;
    }

    $class = parameterClassName($operationId);
    $uses = [];
    $properties = [];
    $headers = [];
    $types = [];
    $required = [];
    foreach ($parameters as $parameter) {
        $schema = $parameter['schema'];
        $name = parameterPropertyName($parameter);
        $enum = null;
        $itemEnum = null;
        $doc = null;
        $date = null;
        if ('include' === $parameter['name']) {
            $php = '?array';
            $doc = 'null|list<string>';
            $itemEnum = INCLUDE_ENUMS[$operationId] ?? className(refName($schema['items']['$ref']));
        } elseif (isset($schema['$ref'])) {
            $php = '?string';
            $enum = 'Id' === refName($schema['$ref']) ? null : className(refName($schema['$ref']));
        } elseif ('array' === ($schema['type'] ?? null)) {
            $php = '?array';
            $doc = 'null|list<string>';
            $itemRef = isset($schema['items']['$ref']) ? refName($schema['items']['$ref']) : null;
            $itemEnum = null !== $itemRef && isset($schemas[$itemRef]['enum']) ? className($itemRef) : null;
        } elseif ('string' === $schema['type'] && \in_array($schema['format'] ?? null, ['date', 'date-time'], true)) {
            $php = '?\DateTimeInterface';
            $date = 'date' === $schema['format'] ? 'date' : null;
        } else {
            $php = ['string' => '?string', 'integer' => '?int', 'boolean' => '?bool'][$schema['type']];
        }

        $lines = sentences($parameter['description'] ?? null);
        if (null !== $enum) {
            $lines[] = 'One of the {@see ' . $enum . '} constants.';
            $uses[] = 'VoucherlyApi\Enum\\' . $enum;
        }
        if (null !== $itemEnum) {
            $lines[] = 'Each item is one of the {@see ' . $itemEnum . '} constants.';
            $uses[] = 'VoucherlyApi\Enum\\' . $itemEnum;
        }
        if ($parameter['required'] ?? false) {
            $php = substr($php, 1);
            $required[] = [$php, $name];
        }
        $properties[] = docBlock($lines, '    ', null !== $doc ? ['@var ' . $doc] : []) . "    public {$php} \${$name};\n";
        if ('header' === $parameter['in']) {
            $headers[] = "'{$name}' => '{$parameter['name']}'";
        }
        if (null !== $date) {
            $types[] = "'{$name}' => '{$date}'";
        }
    }

    $body = implode("\n", $properties);
    if ([] !== $required) {
        $body .= "\n    public function __construct(" . implode(', ', array_map(static fn (array $r): string => "{$r[0]} \${$r[1]}", $required)) . ")\n    {\n";
        foreach ($required as [, $name]) {
            $body .= "        \$this->{$name} = \${$name};\n";
        }
        $body .= "    }\n";
    }
    if ([] !== $headers) {
        $body .= "\n    protected static function headers(): array\n    {\n        return [" . implode(', ', $headers) . "];\n    }\n";
    }
    if ([] !== $types) {
        $body .= "\n    protected static function types(): array\n    {\n        return [" . implode(', ', $types) . "];\n    }\n";
    }

    write("{$lib}/Request/{$class}.php", fileHeader('Request', $uses) . "final class {$class} extends RequestParams\n{\n" . $body . "}\n");
    $written[] = "Request/{$class}.php";
}

$removed = [];
foreach (['Model', 'Request', 'Enum'] as $directory) {
    foreach (glob("{$lib}/{$directory}/*.php") as $file) {
        $relative = $directory . '/' . basename($file);
        if ('index.php' !== basename($file) && !\in_array($relative, $written, true) && !\in_array($relative, HANDWRITTEN_FILES, true)) {
            unlink($file);
            $removed[] = $relative;
        }
    }
}

echo \count($classes) . ' classes, ' . \count($enums) . ' enums, ' . (\count($written) - \count($classes) - \count($enums)) . ' parameter classes written.' . PHP_EOL;
foreach ($removed as $file) {
    echo "Removed {$file}, which the spec no longer declares." . PHP_EOL;
}
