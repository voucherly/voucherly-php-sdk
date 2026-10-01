<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\PaymentGatewayList;
use VoucherlyApi\Request\ListPaymentGatewayParams;

final class PaymentGatewayService extends AbstractService
{
    /**
     * List all PaymentGateways.
     */
    public function list(?ListPaymentGatewayParams $params = null): PaymentGatewayList
    {
        return PaymentGatewayList::constructFrom($this->requestJson('GET', '/v1/payment_gateways', null, $params));
    }
}
