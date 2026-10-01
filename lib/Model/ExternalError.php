<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Error information returned by an external payment gateway provider.
 */
class ExternalError extends VoucherlyObject
{
    /**
     * A human-readable error message from the payment gateway.
     */
    public ?string $message;

    /**
     * An error code from the payment gateway provider.
     */
    public ?string $code;
}
