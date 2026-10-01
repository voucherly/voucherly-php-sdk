<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use VoucherlyApi\VoucherlyClient;

$root = \dirname(__DIR__);
$spec = Yaml::parseFile($root . '/spec/openapi.yaml');
$coverage = json_decode((string) file_get_contents($root . '/spec/coverage.json'), true, 512, JSON_THROW_ON_ERROR);
$mapped = $coverage['operations'] ?? [];
$excluded = $coverage['excluded'] ?? [];

$operations = [];
foreach ($spec['paths'] as $path => $item) {
    foreach ($item as $method => $operation) {
        if (\in_array($method, ['get', 'post', 'put', 'patch', 'delete'], true) && isset($operation['operationId'])) {
            $operations[$operation['operationId']] = strtoupper($method) . ' ' . $path;
        }
    }
}

$errors = [];
foreach ($operations as $id => $route) {
    if (!isset($mapped[$id]) && !isset($excluded[$id])) {
        $errors[] = "{$id} ({$route}) is neither mapped nor excluded.";
    }
}
foreach (array_keys($mapped + $excluded) as $id) {
    if (!isset($operations[$id])) {
        $errors[] = "{$id} is in coverage.json but no longer in the spec.";
    }
}
foreach (array_keys(array_intersect_key($mapped, $excluded)) as $id) {
    $errors[] = "{$id} is both mapped and excluded.";
}
foreach ($excluded as $id => $reason) {
    if (!\is_string($reason) || '' === trim($reason)) {
        $errors[] = "{$id} is excluded without a reason.";
    }
}
foreach (array_count_values($mapped) as $target => $count) {
    if ($count > 1) {
        $errors[] = "{$target} is mapped to {$count} operations.";
    }
}

$client = new VoucherlyClient(['apiKey' => 'sk_sand_coverage']);
foreach ($mapped as $id => $target) {
    $parts = explode('.', (string) $target);
    if (2 !== \count($parts)) {
        $errors[] = "{$id} maps to '{$target}', which is not in the form service.method.";

        continue;
    }
    [$service, $method] = $parts;
    if (!property_exists($client, $service) || !(new ReflectionProperty($client, $service))->isPublic()) {
        $errors[] = "{$id} maps to {$target}, but VoucherlyClient has no public property {$service}.";

        continue;
    }
    if (!method_exists($client->{$service}, $method) || !(new ReflectionMethod($client->{$service}, $method))->isPublic()) {
        $errors[] = "{$id} maps to {$target}, but " . \get_class($client->{$service}) . " has no public method {$method}.";
    }
}

if ([] !== $errors) {
    fwrite(STDERR, implode(PHP_EOL, $errors) . PHP_EOL . \count($errors) . ' coverage error(s).' . PHP_EOL);

    exit(1);
}

echo 'All ' . \count($operations) . ' operations of the spec are covered: ' . \count($mapped) . ' mapped, ' . \count($excluded) . ' excluded.' . PHP_EOL;
