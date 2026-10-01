<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\LineType;
use VoucherlyApi\Model\PaymentLineUnit;
use VoucherlyApi\VoucherlyObject;

/**
 * Inline description of the product associated with a PaymentLine, used inside the create-payment request body. Contains only product attributes; pricing and discount amounts are line-level fields on `Payments.PaymentLine.Request`.
 * When the line also specifies a `productId`, all fields below are optional and any populated value overrides the configuration of the referenced Product. When `productId` is not specified, `name` must be populated (and `unitAmount` must be populated at the line level).
 */
class PaymentLineRequestProduct extends VoucherlyObject
{
    /**
     * An external reference for the product, such as its SKU or EAN, used by Voucherly to reconcile the product across systems and to correctly compute reporting. Highly recommended whenever the product is not referenced via `productId`. At most 100 characters.
     */
    public ?string $externalId;

    /**
     * The product’s name, meant to be displayable to the customer. Required when `productId` is not specified.
     */
    public ?string $name;

    /**
     * The product’s variant description, meant to be displayable to the customer.
     */
    public ?string $variant;

    /**
     * The product’s image URL, meant to be displayable to the customer.
     */
    public ?string $image;

    /**
     * The product's applicable tax rate.
     */
    public ?float $taxRate;

    /**
     * What the line is. Defaults to `NonFood` when omitted, so a grocery item has to declare `Food` to be payable with meal vouchers.
     * One of the {@see LineType} constants.
     */
    public ?string $lineType;

    /**
     * How the line is sold when the piece is not what it is priced by. Declare it on every line whose price can change at delivery, since only such a line accepts `pieces` at confirmation.
     */
    public ?PaymentLineUnit $unit;

    protected static function types(): array
    {
        return [
            'unit' => PaymentLineUnit::class,
        ];
    }
}
