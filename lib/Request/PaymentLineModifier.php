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

    /**
     * Only on a dish chosen in a bundle group, the options picked from the dish's own groups, such as its size. Each one is charged at its own price on top of the bundle, and takes no options of its own.
     *
     * @var null|list<PaymentLineModifier>
     */
    public ?array $modifiers;

    protected static function types(): array
    {
        return [
            'modifiers' => [self::class],
        ];
    }
}
