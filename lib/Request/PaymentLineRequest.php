<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Request shape of a PaymentLine, used in the create-payment request body. Product attributes are grouped under a nested `product` object; pricing and discount amounts stay at the line level.
 * Each line must specify the product through one of:
 * - `productId` only — all product details are loaded from the referenced Voucherly Product.
 * - `product` only — inline ad-hoc product description; required fields inside `product` must be populated.
 * - `productId` + `product` — the line is linked to the referenced Product, and any field set inside `product` overrides the corresponding value of the Product configuration (useful to force a specific price, name, etc.).
 */
class PaymentLineRequest extends VoucherlyObject
{
    /**
     * The quantity of the line item being purchased.
     */
    public ?int $quantity;

    /**
     * A non-negative integer in cents representing how much to charge for each individual unit. Required when `productId` is not specified.
     */
    public ?int $unitAmount;

    /**
     * A non-negative integer in cents representing the discount applied to each individual unit. This field is mutually exclusive with `discountAmount` — only one of the two may be specified.
     */
    public ?int $unitDiscountAmount;

    /**
     * A non-negative integer in cents representing the total discount applied to the entire line. This field is mutually exclusive with `unitDiscountAmount` — only one of the two may be specified.
     */
    public ?int $discountAmount;

    /**
     * The ID of an existing Product in Voucherly that this PaymentLine refers to. One of `productId` or `product` is required.
     */
    public ?string $productId;

    /**
     * Your own reference of the line, such as the id of the order line in your system, when you have one. It is a reference of the line, not of its product, and at confirmation it pairs the line on its own, which is what tells two lines of the same product apart. At most 100 characters.
     */
    public ?string $externalId;

    /**
     * Inline description of the product associated with a PaymentLine, used inside the create-payment request body. Contains only product attributes; pricing and discount amounts are line-level fields on `Payments.PaymentLine.Request`.
     * When the line also specifies a `productId`, all fields below are optional and any populated value overrides the configuration of the referenced Product. When `productId` is not specified, `name` must be populated (and `unitAmount` must be populated at the line level).
     */
    public ?PaymentLineRequestProduct $product;

    /**
     * @var null|list<PaymentLineModifier>
     */
    public ?array $modifiers;

    protected static function types(): array
    {
        return [
            'product' => PaymentLineRequestProduct::class,
            'modifiers' => [PaymentLineModifier::class],
        ];
    }
}
