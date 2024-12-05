<?php

namespace VoucherlyApi\Payment;

use VoucherlyApi\Request;

class Payment
{
    public function __construct() {}

    public static function create(CreatePaymentRequest $request)
    {
        return Request::post('payments', $request);
    }

    public static function get(string $payment_id)
    {
        return Request::get('payments/' . $payment_id);
    }

    public static function capture(string $payment_id)
    {
        return Request::post('payments/' . $payment_id . '/confirm');
    }

    public static function refund(string $payment_id)
    {
        return Request::post('payments/' . $payment_id . '/refund', [
            'transactions' => null,
        ]);
    }
}
