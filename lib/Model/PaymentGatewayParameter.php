<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * A configuration parameter for a payment gateway, including its current value.
 */
class PaymentGatewayParameter extends VoucherlyObject
{
    /**
     * Unique identifier for this parameter.
     */
    public ?string $id;

    /**
     * A human-readable name for this parameter.
     */
    public ?string $friendlyName;

    /**
     * Indicates whether this parameter contains sensitive information that should be masked or hidden.
     */
    public ?bool $isSecret;

    /**
     * The current value of this parameter. May be null if not configured.
     */
    public ?string $value;
}
