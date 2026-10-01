<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Enum\PaymentGatewayInclude;
use VoucherlyApi\Enum\PaymentMode;
use VoucherlyApi\Enum\RevenuesGrouping;
use VoucherlyApi\Model\PaymentGateway;
use VoucherlyApi\Model\RevenuesDetails;
use VoucherlyApi\Request\ListPaymentGatewayParams;
use VoucherlyApi\Request\VolumesReportParams;
use VoucherlyApi\Tests\Support\ServiceTestCase;

final class PaymentGatewayAndReportServiceTest extends ServiceTestCase
{
    public function testListPaymentGateways(): void
    {
        $json = $this->respondWithSample('list-payment-gateway');
        $params = new ListPaymentGatewayParams();
        $params->storeId = 'sto_1';
        $params->all = true;
        $params->include = [PaymentGatewayInclude::PARAMETERS];

        $list = $this->client->paymentGateways->list($params);

        $this->assertOperation('list-payment-gateway', [], 'storeId=sto_1&all=true&include=Parameters');
        self::assertInstanceOf(PaymentGateway::class, $list->items[0]);
        self::assertReadsEveryMember($json, $list);
    }

    public function testListPaymentGatewaysWithoutParameters(): void
    {
        $this->respondWithSample('list-payment-gateway');

        $this->client->paymentGateways->list();

        $this->assertOperation('list-payment-gateway');
    }

    public function testVolumes(): void
    {
        $json = $this->respondWithSample('volumes-report');
        $params = new VolumesReportParams(new \DateTimeImmutable('2026-09-01T00:00:00Z'), new \DateTimeImmutable('2026-09-30T00:00:00Z'));
        $params->paymentMode = PaymentMode::PAYMENT;
        $params->groupBy = [RevenuesGrouping::STORE, RevenuesGrouping::COMPANY];
        $params->paymentGatewayIds = ['SATISPAY', 'EDENRED'];

        $report = $this->client->reports->volumes($params);

        $this->assertOperation('volumes-report', [], 'fromDate=2026-09-01T00%3A00%3A00%2B00%3A00&toDate=2026-09-30T00%3A00%3A00%2B00%3A00&paymentMode=Payment&groupBy=Store&groupBy=Company&paymentGatewayIds=SATISPAY&paymentGatewayIds=EDENRED');
        self::assertInstanceOf(RevenuesDetails::class, $report->items[0]);
        self::assertReadsEveryMember($json, $report);
    }
}
