<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * The product the line was created with, to pair it by product when the line is sent without its own ids.
 */
class ConfirmPaymentRequestLineProduct extends VoucherlyObject
{
    /**
     * The external reference of the product, as sent in `product.externalId` at creation.
     */
    public ?string $externalId;

    public ?string $name;

    public ?string $variant;
}
