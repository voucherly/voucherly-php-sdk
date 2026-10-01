<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Information about when and whether the checkout was closed by the customer.
 */
class PaymentCloseCheckout extends VoucherlyObject
{
    /**
     * Indicates whether the checkout was closed successfully (true) or cancelled (false).
     */
    public ?bool $success;

    /**
     * The UTC timestamp when the checkout was closed.
     */
    public ?string $date;

    /**
     * Why the checkout could not be closed, as a dotted code such as `Payment.Confirm` or `TableOrder.AlreadyClosed`. Only present when `success` is false.
     */
    public ?string $errorReason;

    /**
     * The payload the failing step attached to `errorReason`, as a JSON string. Its shape depends on the reason.
     */
    public ?string $errorAdditionalData;
}
