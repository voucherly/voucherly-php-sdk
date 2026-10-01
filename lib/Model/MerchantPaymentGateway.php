<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Merchant-specific configuration for a payment gateway.
 */
class MerchantPaymentGateway extends VoucherlyObject
{
    /**
     * The ID of the default payment gateway to use if no specific gateway is selected.
     */
    public ?string $defaultPaymentGatewayId;

    /**
     * Indicates whether this payment gateway should be used as a fallback option when other gateways fail.
     */
    public ?bool $isFallback;
}
