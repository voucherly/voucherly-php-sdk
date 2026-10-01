<?php

namespace VoucherlyApi\Tests\Core;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Http\ApiRequestor;
use VoucherlyApi\Http\HttpRequest;
use VoucherlyApi\Tests\Support\FakeTransport;
use VoucherlyApi\Tests\Support\TestService;
use VoucherlyApi\VoucherlyClient;

final class VoucherlyClientTest extends TestCase
{
    public function testRequiresTheApiKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The apiKey option is required.');

        new VoucherlyClient(['apiKey' => '']);
    }

    public function testRejectsAnUnknownOption(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown VoucherlyClient option: apikey.');

        new VoucherlyClient(['apiKey' => 'sk_sand_test', 'apikey' => 'sk_sand_test']);
    }

    public function testRejectsATransportThatIsNotATransport(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new VoucherlyClient(['apiKey' => 'sk_sand_test', 'transport' => new \stdClass()]);
    }

    public function testRejectsTimeoutsTogetherWithACustomTransport(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new VoucherlyClient(['apiKey' => 'sk_sand_test', 'transport' => new FakeTransport(), 'timeout' => 5]);
    }

    public function testSendsTheKeyAndTheUserAgentAndNoOptionalHeaderByDefault(): void
    {
        $request = $this->sendThrough(['apiKey' => 'sk_sand_test']);

        self::assertSame('https://api.voucherly.it/v1/things', $request->getUrl());
        self::assertSame([
            'Voucherly-API-Key' => 'sk_sand_test',
            'User-Agent' => 'VoucherlyApiPhpSdk/' . VoucherlyClient::VERSION,
            'Accept' => 'application/json',
        ], $request->getHeaders());
    }

    public function testSendsThePlatformAndTelemetryHeadersThatHaveAValue(): void
    {
        $request = $this->sendThrough([
            'apiKey' => 'ik_test',
            'merchantId' => 'b3c4a1d2-0000-4000-8000-000000000001',
            'tenant' => 'sand',
            'baseUrl' => 'http://localhost:5000/',
            'os' => 'PrestaShop',
            'osVersion' => '8.2.1',
            'osFramework' => '',
            'app' => 'voucherly-prestashop',
            'appVersion' => '2.0.0',
            'appHouse' => 'Voucherly',
            'deviceType' => null,
        ]);

        self::assertSame('http://localhost:5000/v1/things', $request->getUrl());
        self::assertSame([
            'Voucherly-API-Key' => 'ik_test',
            'User-Agent' => 'VoucherlyApiPhpSdk/' . VoucherlyClient::VERSION,
            'Voucherly-Merchant-Id' => 'b3c4a1d2-0000-4000-8000-000000000001',
            'Voucherly-Tenant' => 'sand',
            'x-voucherly-os' => 'PrestaShop',
            'x-voucherly-osversion' => '8.2.1',
            'x-voucherly-app' => 'voucherly-prestashop',
            'x-voucherly-appversion' => '2.0.0',
            'x-voucherly-apphouse' => 'Voucherly',
            'Accept' => 'application/json',
        ], $request->getHeaders());
    }

    public function testTheVersionMatchesTheChangelog(): void
    {
        $changelog = (string) @file_get_contents(__DIR__ . '/../../CHANGELOG.md');
        if ('' === $changelog) {
            self::markTestIncomplete('CHANGELOG.md is not written yet.');
        }

        self::assertStringContainsString('## ' . VoucherlyClient::VERSION . ' - ', $changelog);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function sendThrough(array $options): HttpRequest
    {
        $transport = (new FakeTransport())->respond(200, []);
        $client = new VoucherlyClient($options + ['transport' => $transport]);
        $requestor = (fn (): ApiRequestor => $this->requestor)->call($client);

        (new TestService($requestor))->json('GET', '/v1/things');

        return $transport->lastRequest();
    }
}
