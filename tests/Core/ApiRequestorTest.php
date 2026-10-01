<?php

namespace VoucherlyApi\Tests\Core;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Exception\ConnectionException;
use VoucherlyApi\Exception\VoucherlyException;
use VoucherlyApi\Http\ApiRequestor;
use VoucherlyApi\Tests\Support\FakeTransport;
use VoucherlyApi\Tests\Support\SampleObject;
use VoucherlyApi\Tests\Support\SampleParams;
use VoucherlyApi\Tests\Support\TestService;

final class ApiRequestorTest extends TestCase
{
    private FakeTransport $transport;
    private TestService $service;

    protected function setUp(): void
    {
        $this->transport = new FakeTransport();
        $this->service = new TestService(new ApiRequestor('https://api.example.test/', ['Voucherly-API-Key' => 'sk_sand_test'], $this->transport));
    }

    public function testSendsAJsonBodyWithItsContentType(): void
    {
        $this->transport->respond(201, ['id' => 'pay_1']);
        $body = new SampleObject();
        $body->name = 'Caffè €';

        $data = $this->service->json('POST', '/v1/things', $body);

        $request = $this->transport->lastRequest();
        self::assertSame(['id' => 'pay_1'], $data);
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://api.example.test/v1/things', $request->getUrl());
        self::assertSame('{"name":"Caffè €"}', $request->getBody());
        self::assertSame('application/json', $request->getHeaders()['Content-Type']);
        self::assertSame('application/json', $request->getHeaders()['Accept']);
        self::assertSame('sk_sand_test', $request->getHeaders()['Voucherly-API-Key']);
    }

    public function testReplacesInvalidUtf8InsteadOfFailing(): void
    {
        $this->transport->respond(200, []);
        $body = new SampleObject();
        $body->name = "Caff\xE8";

        $this->service->json('POST', '/v1/things', $body);

        self::assertSame('{"name":"Caff' . "\u{FFFD}" . '"}', $this->transport->lastRequest()->getBody());
    }

    public function testSendsAnEmptyJsonObjectOnAPostWithoutARequestObject(): void
    {
        $this->transport->respond(200, []);

        $this->service->json('POST', '/v1/things/1/void');

        $request = $this->transport->lastRequest();
        self::assertSame('{}', $request->getBody());
        self::assertSame('application/json', $request->getHeaders()['Content-Type']);
    }

    public function testSendsNoBodyAndNoContentTypeOnAGet(): void
    {
        $this->transport->respond(200, []);

        $this->service->json('GET', '/v1/things/1');

        $request = $this->transport->lastRequest();
        self::assertNull($request->getBody());
        self::assertArrayNotHasKey('Content-Type', $request->getHeaders());
    }

    public function testAppendsTheQueryAndTheHeaderParameters(): void
    {
        $this->transport->respond(200, []);
        $params = new SampleParams();
        $params->include = ['Lines'];
        $params->waitTime = 10;

        $this->service->json('GET', '/v1/things/1', null, $params);

        $request = $this->transport->lastRequest();
        self::assertSame('https://api.example.test/v1/things/1?include=Lines', $request->getUrl());
        self::assertSame('10', $request->getHeaders()['Voucherly-Wait-Time']);
    }

    public function testReturnsTheBytesOfADownload(): void
    {
        $this->transport->respond(200, "%PDF-1.7\x00\xFF", ['content-type' => 'application/pdf']);

        $bytes = $this->service->bytes('/v1/receipts/rcp_1/download');

        self::assertSame("%PDF-1.7\x00\xFF", $bytes);
        self::assertSame('application/pdf', $this->transport->lastRequest()->getHeaders()['Accept']);
    }

    public function testAcceptsAnEmptyNoContentResponse(): void
    {
        $this->transport->respond(204);

        $this->service->noContent('DELETE', '/v1/things/1');

        self::assertSame('DELETE', $this->transport->lastRequest()->getMethod());
    }

    public function testRejectsAResponseThatIsNotJson(): void
    {
        $this->transport->respond(200, '<html>');

        $this->expectException(VoucherlyException::class);
        $this->expectExceptionMessage('The response body is not valid JSON');

        $this->service->json('GET', '/v1/things');
    }

    public function testLetsTheConnectionExceptionOfTheTransportThrough(): void
    {
        $this->transport->fail(new ConnectionException('Operation timed out after 30000 milliseconds', 28));

        $this->expectException(ConnectionException::class);
        $this->expectExceptionCode(28);

        $this->service->json('GET', '/v1/things');
    }

    public function testEncodesEachIdAsAPathSegment(): void
    {
        self::assertSame('/v1/customers/cs%2F1/addresses/a%20b', TestService::buildPath('/v1/customers/%s/addresses/%s', 'cs/1', 'a b'));
    }

    public function testRejectsAnEmptyId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TestService::buildPath('/v1/payments/%s', '');
    }
}
