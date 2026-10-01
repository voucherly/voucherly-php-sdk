<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\LineOrigin;
use VoucherlyApi\Enum\LineType;
use VoucherlyApi\VoucherlyObject;

/**
 * Response shape of a PaymentLine, returned within a Payment object.
 * Note: when creating a Payment, the request body uses a different, normalized shape that nests product fields under a `product` object. See `Payments.PaymentLine.Request` for the create-payment request line shape.
 */
class PaymentLine extends VoucherlyObject
{
    /**
     * The quantity of the line item being purchased.
     */
    public ?int $quantity;

    /**
     * A non-negative integer in cents representing how much to charge for each individual unit.
     */
    public ?int $unitAmount;

    /**
     * A non-negative integer in cents representing the discount applied to each individual unit.
     */
    public ?int $unitDiscountAmount;

    /**
     * A non-negative integer in cents representing the total discount applied to the entire line.
     */
    public ?int $discountAmount;

    /**
     * `unitAmount` times `quantity`, in cents. Compute it from those two instead.
     *
     * @deprecated
     */
    public ?int $totalAmount;

    /**
     * `unitDiscountAmount` times `quantity`, in cents. Compute it from those two instead.
     *
     * @deprecated
     */
    public ?int $totalDiscountAmount;

    /**
     * A non-negative integer in cents representing the final amount charged for this line, after discounts.
     */
    public ?int $finalAmount;

    /**
     * The ID of the Product that this PaymentLine refers to, when the line was created against an existing Voucherly Product.
     */
    public ?string $productId;

    /**
     * The product’s name, meant to be displayable to the customer.
     */
    public ?string $productName;

    /**
     * The product’s variant description, meant to be displayable to the customer.
     */
    public ?string $productVariant;

    /**
     * The product’s image URL, meant to be displayable to the customer.
     */
    public ?string $productImage;

    /**
     * The external reference of the product of the line, as sent in `product.externalId`: the SKU or code you use for it. Distinct from the reference of the line.
     */
    public ?string $productExternalId1;

    /**
     * Your own reference of the line, as sent at creation, typically the id of the order line in your system. At confirmation it pairs the line on its own.
     */
    public ?string $externalId;

    /**
     * How the line is sold when the piece is not what it is priced by, as sent at creation. Absent on a line sold by the piece.
     */
    public ?PaymentLineUnit $unit;

    /**
     * The product's applicable tax rate.
     */
    public ?float $taxRate;

    /**
     * One of the {@see LineType} constants.
     */
    public ?string $lineType;

    /**
     * True when `lineType` is `Food`. Read `lineType` instead.
     *
     * @deprecated
     */
    public ?bool $isFood;

    /**
     * Indicates whether this PaymentLine is a gift item.
     */
    public ?bool $isGift;

    /**
     * One of the {@see LineOrigin} constants.
     */
    public ?string $origin;

    /**
     * The quantity the confirmation accounted for, when it differs from the one ordered. Absent while the line stands as ordered, which is what every other field describes.
     */
    public ?int $confirmedQuantity;

    /**
     * The final amount of the line as confirmed, in cents, discount of the line included. Absent together with `confirmedQuantity`.
     */
    public ?int $confirmedFinalAmount;

    /**
     * The pieces the line was confirmed with, one per item delivered, when the confirmation sent them. Their amounts add up to `confirmedFinalAmount`, which may then exceed `finalAmount`.
     *
     * @var null|list<PaymentLinePiece>
     */
    public ?array $confirmedPieces;

    protected static function types(): array
    {
        return [
            'unit' => PaymentLineUnit::class,
            'confirmedPieces' => [PaymentLinePiece::class],
        ];
    }
}
