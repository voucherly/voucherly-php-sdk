<?php

namespace VoucherlyApi\Tests\Core;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Exception\ConnectionException;
use VoucherlyApi\Http\CurlTransport;
use VoucherlyApi\Http\HttpRequest;

final class CurlTransportTest extends TestCase
{
    /** @var null|resource */
    private static $server;

    private static string $baseUrl;

    public static function setUpBeforeClass(): void
    {
        $port = self::freePort();
        self::$baseUrl = 'http://127.0.0.1:' . $port;
        self::$server = proc_open(
            [PHP_BINARY, '-S', '127.0.0.1:' . $port, __DIR__ . '/../Support/echo-server.php'],
            [['pipe', 'r'], ['file', \PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null', 'w'], ['file', \PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null', 'w']],
            $pipes
        );

        $deadline = microtime(true) + 10;
        while (false === @fsockopen('127.0.0.1', $port)) {
            if (microtime(true) > $deadline) {
                self::fail('The PHP built-in server did not start.');
            }
            usleep(50000);
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (\is_resource(self::$server)) {
            proc_terminate(self::$server);
            proc_close(self::$server);
        }
    }

    public function testSendsMethodHeadersAndBody(): void
    {
        $response = (new CurlTransport())->send(new HttpRequest('POST', self::$baseUrl . '/v1/payments?include=Lines', [
            'Voucherly-API-Key' => 'sk_sand_test',
            'Content-Type' => 'application/json',
        ], str_repeat('{"a":1}', 300)));

        $echo = json_decode($response->getBody(), true);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('yes', $response->getHeaders()['x-echo']);
        self::assertSame('POST', $echo['method']);
        self::assertSame('/v1/payments?include=Lines', $echo['uri']);
        self::assertSame('sk_sand_test', $echo['headers']['voucherly-api-key']);
        self::assertSame('application/json', $echo['headers']['content-type']);
        self::assertArrayNotHasKey('expect', $echo['headers']);
        self::assertSame(str_repeat('{"a":1}', 300), $echo['body']);
    }

    public function testSendsAZeroContentLengthOnAPostWithoutBody(): void
    {
        $response = (new CurlTransport())->send(new HttpRequest('POST', self::$baseUrl . '/v1/payments/pay_1/void', [], null));

        $echo = json_decode($response->getBody(), true);
        self::assertSame('POST', $echo['method']);
        self::assertSame('0', $echo['headers']['content-length']);
        self::assertSame('', $echo['body']);
    }

    public function testReturnsAnErrorStatusWithoutThrowing(): void
    {
        $response = (new CurlTransport())->send(new HttpRequest('DELETE', self::$baseUrl . '/status/404', [], null));

        self::assertSame(404, $response->getStatusCode());
        self::assertSame('DELETE', json_decode($response->getBody(), true)['method']);
    }

    public function testThrowsAConnectionExceptionWhenTheConnectionIsRefused(): void
    {
        $this->expectException(ConnectionException::class);

        (new CurlTransport(2, 2))->send(new HttpRequest('GET', 'http://127.0.0.1:' . self::freePort() . '/', [], null));
    }

    public function testThrowsAConnectionExceptionOnTimeout(): void
    {
        $this->expectException(ConnectionException::class);
        $this->expectExceptionCode(CURLE_OPERATION_TIMEDOUT);

        (new CurlTransport(2, 0.3))->send(new HttpRequest('GET', self::$baseUrl . '/sleep', [], null));
    }

    private static function freePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0');
        $name = stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr((string) strrchr((string) $name, ':'), 1);
    }
}
