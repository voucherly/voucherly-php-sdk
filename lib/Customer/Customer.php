<?php

namespace VoucherlyApi\Customer;

use VoucherlyApi\Request;

class Customer
{
    public function __construct() {}

    public static function paymentMethods(string $customerId)
    {
        return Request::get('customers/' . $customerId . '/payment_methods');
    }
}
