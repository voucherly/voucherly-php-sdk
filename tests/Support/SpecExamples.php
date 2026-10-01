<?php

namespace VoucherlyApi\Tests\Support;

use Symfony\Component\Yaml\Yaml;

final class SpecExamples
{
    /** @var null|array<string, mixed> */
    private static $spec;

    /**
     * @return array<string, mixed>
     */
    public static function spec(): array
    {
        if (null === self::$spec) {
            self::$spec = Yaml::parseFile(__DIR__ . '/../../spec/openapi.yaml');
        }

        return self::$spec;
    }

    /**
     * @return array<string, mixed> the operation, with its path and method
     */
    public static function operation(string $operationId): array
    {
        foreach (self::spec()['paths'] as $path => $item) {
            foreach ($item as $method => $operation) {
                if (\is_array($operation) && ($operation['operationId'] ?? null) === $operationId) {
                    return $operation + ['path' => $path, 'method' => strtoupper($method)];
                }
            }
        }

        throw new \InvalidArgumentException("The spec has no operation {$operationId}.");
    }

    /**
     * The request body examples of an operation, by name; an inline example is named 'default'.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function requestExamples(string $operationId): array
    {
        $content = self::operation($operationId)['requestBody']['content']['application/json'] ?? [];

        return self::examples($content);
    }

    /**
     * The success status of an operation, with a body built from its schema that holds every property the spec documents.
     *
     * @return array{0: int, 1: mixed}
     */
    public static function successResponse(string $operationId): array
    {
        foreach (self::operation($operationId)['responses'] as $status => $response) {
            if ((int) $status >= 200 && (int) $status < 300) {
                $schema = $response['content']['application/json']['schema'] ?? null;

                return [(int) $status, null === $schema ? null : self::sample($schema)];
            }
        }

        throw new \InvalidArgumentException("The operation {$operationId} has no success response.");
    }

    /**
     * A value for the schema: its example when it has one, otherwise a value of its type, with every property of an object.
     *
     * @param array<string, mixed> $schema
     *
     * @return mixed
     */
    public static function sample(array $schema)
    {
        $schema = self::resolve($schema);
        if (isset($schema['allOf'])) {
            return self::sample($schema['allOf'][0]);
        }
        if (isset($schema['enum'])) {
            return $schema['enum'][0];
        }
        if (\array_key_exists('example', $schema)) {
            return $schema['example'];
        }

        switch ($schema['type'] ?? 'object') {
            case 'array':
                return [self::sample($schema['items'])];

            case 'integer':
                return 1;

            case 'number':
                return 22.5;

            case 'boolean':
                return true;

            case 'string':
                return ['date-time' => '2026-09-30T10:15:00Z', 'date' => '2026-09-30', 'uuid' => '0b4c6f3e-2d1a-4c5b-9e8f-7a6b5c4d3e2f', 'uri' => 'https://example.com/pos'][$schema['format'] ?? ''] ?? 'text';

            default:
                if (!isset($schema['properties'])) {
                    return ['key' => \is_array($schema['additionalProperties'] ?? null) ? self::sample($schema['additionalProperties']) : 'value'];
                }
                $object = [];
                foreach ($schema['properties'] as $name => $property) {
                    $object[$name] = self::sample($property);
                }

                return $object;
        }
    }

    /**
     * @param array<string, mixed> $node
     *
     * @return array<string, mixed>
     */
    public static function resolve(array $node): array
    {
        while (isset($node['$ref'])) {
            $target = self::spec();
            foreach (explode('/', substr($node['$ref'], 2)) as $segment) {
                $target = $target[$segment];
            }
            $node = $target;
        }

        return $node;
    }

    /**
     * Every example of every error response declared by the spec, as [operationId, status, example name, body].
     *
     * @return list<array{0: string, 1: int, 2: string, 3: array<string, mixed>}>
     */
    public static function errorExamples(): array
    {
        $examples = [];
        foreach (self::spec()['paths'] as $item) {
            foreach ($item as $operation) {
                if (!\is_array($operation) || !isset($operation['operationId'])) {
                    continue;
                }
                foreach ($operation['responses'] ?? [] as $status => $response) {
                    if ((int) $status < 400) {
                        continue;
                    }
                    $response = self::resolve($response);
                    foreach (self::examples($response['content']['application/json'] ?? []) as $name => $body) {
                        $examples[] = [$operation['operationId'], (int) $status, $name, $body];
                    }
                }
            }
        }

        return $examples;
    }

    /**
     * @param array<string, mixed> $content
     *
     * @return array<string, array<string, mixed>>
     */
    private static function examples(array $content): array
    {
        $examples = [];
        if (isset($content['example'])) {
            $examples['default'] = $content['example'];
        }
        foreach ($content['examples'] ?? [] as $name => $example) {
            $examples[$name] = self::resolve($example)['value'];
        }

        return $examples;
    }
}
