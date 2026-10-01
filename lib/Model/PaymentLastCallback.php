<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Information about the last callback sent to your callbackUrl endpoint.
 */
class PaymentLastCallback extends VoucherlyObject
{
    /**
     * Indicates whether the callback was sent successfully (true) or failed (false).
     */
    public ?bool $success;

    /**
     * The UTC timestamp when the last callback was sent.
     */
    public ?string $date;
}
