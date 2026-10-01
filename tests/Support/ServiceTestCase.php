<?php

namespace VoucherlyApi\Tests\Support;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Http\HttpRequest;
use VoucherlyApi\VoucherlyClient;
use VoucherlyApi\VoucherlyObject;

abstract class ServiceTestCase extends TestCase
{
    protected FakeTransport $transport;
    protected VoucherlyClient $client;

    protected function setUp(): void
    {
        $this->transport = new FakeTransport();
        $this->client = new VoucherlyClient(['apiKey' => 'sk_sand_test', 'transport' => $this->transport]);
    }

    /**
     * Queues the success response of the operation, with a body that holds every property the spec documents.
     *
     * @return mixed the body
     */
    protected function respondWithSample(string $operationId)
    {
        [$status, $body] = SpecExamples::successResponse($operationId);
        $this->transport->respond($status, null === $body ? '' : $body);

        return $body;
    }

    /**
     * Asserts that the last request is the operation of the spec, with the given path parameters, query and JSON body.
     *
     * @param array<string, string>     $pathParameters
     * @param null|array<string, mixed> $body           null when the request must have no body
     */
    protected function assertOperation(string $operationId, array $pathParameters = [], string $query = '', ?array $body = null): HttpRequest
    {
        $operation = SpecExamples::operation($operationId);
        $path = preg_replace_callback('/\{(\w+)\}/', static fn (array $match): string => rawurlencode($pathParameters[$match[1]]), $operation['path']);
        $request = $this->transport->lastRequest();

        self::assertCount(1, $this->transport->requests);
        self::assertSame($operation['method'], $request->getMethod());
        self::assertSame('https://api.voucherly.it' . $path . ('' !== $query ? '?' . $query : ''), $request->getUrl());
        self::assertSame('sk_sand_test', $request->getHeaders()['Voucherly-API-Key']);

        if (null === $body) {
            self::assertNull($request->getBody());
            self::assertArrayNotHasKey('Content-Type', $request->getHeaders());
        } else {
            self::assertSame('application/json', $request->getHeaders()['Content-Type']);
            self::assertEquals($body, json_decode((string) $request->getBody(), true));
        }

        return $request;
    }

    /**
     * Asserts that the object holds every member of the JSON it was read from, and nothing else.
     *
     * @param mixed $json
     */
    protected static function assertReadsEveryMember($json, VoucherlyObject $object): void
    {
        self::assertNoExtensionData($object);
        self::assertEquals($json, json_decode(json_encode($object), true));
    }

    /**
     * @param mixed $value
     */
    protected static function assertNoExtensionData($value, string $path = '$'): void
    {
        if ($value instanceof VoucherlyObject) {
            self::assertSame([], $value->getExtensionData(), "Unknown members at {$path}.");
            foreach (get_object_vars($value) as $name => $property) {
                self::assertNoExtensionData($property, "{$path}.{$name}");
            }
        } elseif (\is_array($value)) {
            foreach ($value as $key => $item) {
                self::assertNoExtensionData($item, "{$path}[{$key}]");
            }
        }
    }
}
