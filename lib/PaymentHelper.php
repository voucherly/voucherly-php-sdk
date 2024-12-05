<?php

namespace VoucherlyApi;

class PaymentHelper
{
    public static function isPaidOrCaptured($payment)
    {
        return 'Confirmed' === $payment->status || 'Captured' === $payment->status || 'Paid' === $payment->status;
    }
}
