<?php

namespace VoucherlyApi\PaymentGateway;

use VoucherlyApi\Request;

class PaymentGateway
{
    public function __construct() {}

    public static function list()
    {
        return Request::get('payment_gateways');
    }
}
