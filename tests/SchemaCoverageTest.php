<?php

namespace VoucherlyApi\Tests;

use VoucherlyApi\Tests\Support\ServiceTestCase;
use VoucherlyApi\Tests\Support\SpecExamples;
use VoucherlyApi\VoucherlyObject;

/**
 * Checks the classes against the schemas of the spec, so that a changed spec fails here until the SDK follows it.
 */
final class SchemaCoverageTest extends ServiceTestCase
{
    private const GROUPS = ['Payments', 'Customers', 'Stores', 'PaymentGateways', 'Receipts', 'Terminals', 'Reports'];

    private const INLINE_ENUMS = [
        'Company.packagingType' => 'PackagingType',
        'Customers.Packaging.type' => 'PackagingType',
        'CreatePaymentRequest.completionMode' => 'CompletionMode',
        'CreatePaymentRequest.exceedingAmountMode' => 'ExceedingAmountMode',
        'Stores.StoreStatus.status' => 'StoreStatusValue',
    ];

    private const INCLUDE_ENUMS = [
        'retrieve-company' => 'CompanyInclude',
        'retrieve-customer' => 'CustomerInclude',
        'retrieve-payment' => 'PaymentInclude',
        'list-payment-gateway' => 'PaymentGatewayInclude',
    ];

    /**
     * @dataProvider provideEveryRequestBodyAndExampleRoundTripsThroughItsClassCases
     *
     * @param class-string<VoucherlyObject> $class
     * @param array<string, mixed>          $json
     */
    public function testEveryRequestBodyAndExampleRoundTripsThroughItsClass(string $class, array $json): void
    {
        $request = $class::fromArray($json);

        self::assertReadsEveryMember($json, $request);
    }

    /**
     * @return iterable<string, array{string, array<string, mixed>}>
     */
    public static function provideEveryRequestBodyAndExampleRoundTripsThroughItsClassCases(): iterable
    {
        foreach (self::operations() as $operationId => $operation) {
            $ref = $operation['requestBody']['content']['application/json']['schema']['$ref'] ?? null;
            if (null !== $ref) {
                $class = 'VoucherlyApi\Request\\' . self::className(substr($ref, strrpos($ref, '/') + 1));

                yield "{$operationId} schema" => [$class, SpecExamples::sample(['$ref' => $ref])];
                foreach (SpecExamples::requestExamples($operationId) as $name => $example) {
                    yield "{$operationId} {$name}" => [$class, $example];
                }
            }
        }
    }

    public function testEveryEnumHasTheValuesOfTheSpec(): void
    {
        $enums = [];
        foreach (SpecExamples::spec()['components']['schemas'] as $key => $schema) {
            if (isset($schema['enum'])) {
                $enums[self::className($key)] = $schema['enum'];
            }
            foreach (self::inlineEnums($key, $schema) as $path => $values) {
                self::assertArrayHasKey($path, self::INLINE_ENUMS, "The inline enum {$path} needs a class name.");
                $enums[self::INLINE_ENUMS[$path]] = $values;
            }
        }
        foreach (self::operations() as $operationId => $operation) {
            foreach ($operation['parameters'] ?? [] as $parameter) {
                if (isset($parameter['schema']['items']['enum'])) {
                    self::assertArrayHasKey($operationId, self::INCLUDE_ENUMS, "The enum of {$operationId} needs a class name.");
                    $enums[self::INCLUDE_ENUMS[$operationId]] = $parameter['schema']['items']['enum'];
                }
            }
        }

        foreach ($enums as $class => $values) {
            $constants = array_values((new \ReflectionClass('VoucherlyApi\Enum\\' . $class))->getConstants());
            sort($constants);
            sort($values);
            self::assertSame($values, $constants, $class);
        }
    }

    public function testEveryParameterOfTheSpecHasAProperty(): void
    {
        foreach (self::operations() as $operationId => $operation) {
            $expected = [];
            foreach ($operation['parameters'] ?? [] as $parameter) {
                $parameter = SpecExamples::resolve($parameter);
                if ('query' === $parameter['in']) {
                    $expected[] = $parameter['name'];
                } elseif ('header' === $parameter['in']) {
                    $words = explode('-', str_replace('Voucherly-', '', $parameter['name']));
                    $expected[] = strtolower(array_shift($words)) . implode('', array_map('ucfirst', array_map('strtolower', $words)));
                }
            }

            $class = 'VoucherlyApi\Request\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $operationId))) . 'Params';
            if ([] === $expected) {
                self::assertFalse(class_exists($class), "{$class} has no parameter to hold.");

                continue;
            }

            $properties = array_map(static fn (\ReflectionProperty $property): string => $property->getName(), (new \ReflectionClass($class))->getProperties(\ReflectionProperty::IS_PUBLIC));
            sort($properties);
            sort($expected);
            self::assertSame($expected, $properties, $class);
        }
    }

    public function testEveryRequestPropertyAcceptsNullUnlessTheSpecForbidsIt(): void
    {
        $checked = 0;
        foreach (SpecExamples::spec()['components']['schemas'] as $key => $schema) {
            $checked += self::assertNullability('VoucherlyApi\Request\\' . self::className($key), $schema);
        }

        self::assertGreaterThan(0, $checked);
    }

    /**
     * The class of a schema: the name without its group prefix, with the remaining segments joined.
     */
    private static function className(string $schemaKey): string
    {
        $renames = ['CompanyAddressForExternalApi' => 'CompanyAddress', 'PaymentGateways.GetPaymentGatewaysResponse' => 'PaymentGatewayList', 'PaginationResponse' => 'Pagination'];
        if (isset($renames[$schemaKey])) {
            return $renames[$schemaKey];
        }

        $segments = explode('.', $schemaKey);
        if (\in_array($segments[0], self::GROUPS, true)) {
            array_shift($segments);
        }

        return implode('', array_map('ucfirst', $segments));
    }

    /**
     * @param array<string, mixed> $schema
     */
    private static function assertNullability(string $class, array $schema): int
    {
        if (!class_exists($class)) {
            return 0;
        }

        $checked = 0;
        foreach ($schema['properties'] ?? [] as $name => $property) {
            $type = (new \ReflectionProperty($class, '$type' === $name ? 'type' : (string) $name))->getType();
            self::assertSame(false !== ($property['nullable'] ?? null), null !== $type && $type->allowsNull(), "{$class}::\${$name}");
            ++$checked;

            $inner = 'array' === ($property['type'] ?? null) ? ($property['items'] ?? []) : $property;
            if ('object' === ($inner['type'] ?? null)) {
                $checked += self::assertNullability($class . ucfirst((string) $name), $inner);
            }
        }

        return $checked;
    }

    /**
     * @param array<string, mixed> $schema
     *
     * @return array<string, list<mixed>>
     */
    private static function inlineEnums(string $path, array $schema): array
    {
        $enums = [];
        foreach ($schema['properties'] ?? [] as $name => $property) {
            if (isset($property['enum'])) {
                $enums["{$path}.{$name}"] = $property['enum'];
            }
            $enums += self::inlineEnums("{$path}.{$name}", $property);
        }

        return $enums;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function operations(): array
    {
        $operations = [];
        foreach (SpecExamples::spec()['paths'] as $item) {
            foreach ($item as $operation) {
                if (\is_array($operation) && isset($operation['operationId'])) {
                    $operations[$operation['operationId']] = $operation;
                }
            }
        }

        return $operations;
    }
}
