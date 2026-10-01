<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * The prepaid balance available to a Customer for a given date.
 */
class CustomerPrepaidBalance extends VoucherlyObject
{
    /**
     * Indicates whether the prepaid PaymentGateway is currently configured and active for the Merchant.
     */
    public ?bool $paymentGatewayIsActive;

    public ?PrepaidPolicy $policy;

    /**
     * The remaining amount, in cents, that the Customer is still allowed to spend on the requested Date.
     */
    public ?int $availableAmount;

    /**
     * The amount, in cents, already spent by the Customer on the requested Date.
     */
    public ?int $usedAmount;

    /**
     * The Date the balance refers to.
     */
    public ?string $date;

    protected static function types(): array
    {
        return [
            'policy' => PrepaidPolicy::class,
        ];
    }
}
