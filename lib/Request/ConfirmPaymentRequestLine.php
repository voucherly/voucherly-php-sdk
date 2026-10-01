<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Model\PaymentLinePiece;
use VoucherlyApi\VoucherlyObject;

class ConfirmPaymentRequestLine extends VoucherlyObject
{
    /**
     * The quantity delivered, from `0` to the quantity ordered.
     * Required.
     */
    public ?int $quantity;

    /**
     * Your own reference of the line, as sent at creation. It pairs the line on its own; a value that matches no line of the Payment fails with `LINES_MISMATCH`, it never falls back to the product.
     */
    public ?string $externalId;

    /**
     * The ID of the Product of the line, when it was created with one.
     */
    public ?string $productId;

    /**
     * The product the line was created with, to pair it by product when the line is sent without its own ids.
     */
    public ?ConfirmPaymentRequestLineProduct $product;

    /**
     * One entry per item delivered, only on a line created with `product.unit`, as many as `quantity`. Send it when the amount charged differs from the ordered price, as it does with variable-weight items; without it the line is accounted at the ordered price. Fails with `PIECES_NOT_ALLOWED` on a line without `product.unit`, and with `PIECES_COUNT_MISMATCH` when the count differs from `quantity`.
     *
     * @var null|list<PaymentLinePiece>
     */
    public ?array $pieces;

    protected static function types(): array
    {
        return [
            'product' => ConfirmPaymentRequestLineProduct::class,
            'pieces' => [PaymentLinePiece::class],
        ];
    }
}
