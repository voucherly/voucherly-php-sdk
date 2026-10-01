<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Model\PaymentMethod;
use VoucherlyApi\Request\ListCustomerPaymentMethodParams;
use VoucherlyApi\Tests\Support\ServiceTestCase;

final class PaymentMethodServiceTest extends ServiceTestCase
{
    public function testList(): void
    {
        $json = $this->respondWithSample('list-customer-payment-method');

        $page = $this->client->paymentMethods->list('cs_YZOJp96qKlW');

        $this->assertOperation('list-customer-payment-method', ['customerId' => 'cs_YZOJp96qKlW']);
        self::assertInstanceOf(PaymentMethod::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testListWithParams(): void
    {
        $json = $this->respondWithSample('list-customer-payment-method');
        $params = new ListCustomerPaymentMethodParams();
        $params->length = 10;
        $params->start = 'pm_next';

        $page = $this->client->paymentMethods->list('cs_YZOJp96qKlW', $params);

        $this->assertOperation('list-customer-payment-method', ['customerId' => 'cs_YZOJp96qKlW'], 'length=10&start=pm_next');
        self::assertInstanceOf(PaymentMethod::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testDelete(): void
    {
        $this->respondWithSample('delete-customer-payment-method');

        $this->client->paymentMethods->delete('cs_YZOJp96qKlW', 'pm_1');

        $this->assertOperation('delete-customer-payment-method', ['customerId' => 'cs_YZOJp96qKlW', 'paymentMethodId' => 'pm_1']);
    }
}
