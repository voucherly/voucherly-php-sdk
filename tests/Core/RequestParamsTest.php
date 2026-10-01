<?php

namespace VoucherlyApi\Tests\Core;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Tests\Support\SampleParams;

final class RequestParamsTest extends TestCase
{
    public function testSendsNothingWhenNothingIsAssigned(): void
    {
        $params = new SampleParams();

        self::assertSame('', $params->toQueryString());
        self::assertSame([], $params->toHeaders());
    }

    public function testFormatsEachTypeOfValue(): void
    {
        $params = new SampleParams();
        $params->name = 'Bar & Pizzeria';
        $params->isActive = false;
        $params->include = ['Lines', 'Transactions'];
        $params->fromDate = new \DateTimeImmutable('2026-09-01 00:00:00', new \DateTimeZone('Europe/Rome'));
        $params->date = new \DateTimeImmutable('2026-09-30 18:00:00');
        $params->length = 100;

        self::assertSame(
            'name=Bar%20%26%20Pizzeria&isActive=false&include=Lines&include=Transactions&fromDate=2026-09-01T00%3A00%3A00%2B02%3A00&date=2026-09-30&length=100',
            $params->toQueryString()
        );
    }

    public function testSkipsNullValues(): void
    {
        $params = new SampleParams();
        $params->name = null;
        $params->waitTime = null;

        self::assertSame('', $params->toQueryString());
        self::assertSame([], $params->toHeaders());
    }

    public function testSendsHeaderParametersAsHeadersOnly(): void
    {
        $params = new SampleParams();
        $params->waitTime = 30;

        self::assertSame('', $params->toQueryString());
        self::assertSame(['Voucherly-Wait-Time' => '30'], $params->toHeaders());
    }
}
