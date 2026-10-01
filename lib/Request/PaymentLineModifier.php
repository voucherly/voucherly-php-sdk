<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * A modifier or customization applied to a payment line item (e.g., extra toppings, size options).
 */
class PaymentLineModifier extends VoucherlyObject
{
    /**
     * The ID of the Modifier that this customization belongs to.
     */
    public string $modifierId;

    /**
     * The ID of the Product that this customization belongs to.
     */
    public string $productId;

    /**
     * The quantity of this modifier.
     */
    public ?int $quantity;
}
