<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\PaymentInclude;

final class RetrievePaymentParams extends RequestParams
{
    /**
     * An array of nested object to be included in response.
     * Each item is one of the {@see PaymentInclude} constants.
     *
     * @var null|list<string>
     */
    public ?array $include;

    /**
     * Seconds that the call will be hanging, waiting for a payment status change. Maximum value is 60 seconds.
     */
    public ?int $waitTime;

    protected static function headers(): array
    {
        return ['waitTime' => 'Voucherly-Wait-Time'];
    }
}
