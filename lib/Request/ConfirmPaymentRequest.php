<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\RefundMode;
use VoucherlyApi\VoucherlyObject;

/**
 * Specify at most one of:
 * - nothing, to capture every authorized transaction in full;
 * - `transactions`, to choose yourself which transactions to capture and for how much;
 * - `lines`, to confirm the quantities actually delivered: Voucherly adds them up, discounts of the Payment included, and settles the transactions on that amount;
 * - `finalAmount` with `foodAmount`, to confirm at an amount without resending the lines.
 * A confirmation accounts, it does not rewrite the order: the lines, the discounts and the amounts of the Payment keep describing what was ordered, and what was settled is in `confirmedAmount`, `cancelledAmount` and `refundedAmount`, and per line in `confirmedQuantity`.
 * With `lines` or with the amounts, meal vouchers cover at most the food amount and the other transactions cover the rest: authorizations are captured partially or voided. Money already captured is given back only as `refundMode` allows.
 */
class ConfirmPaymentRequest extends VoucherlyObject
{
    /**
     * List of transactions to be confirmed.
     *
     * @var null|list<ConfirmPaymentRequestTransaction>
     */
    public ?array $transactions;

    /**
     * Every line of the Payment with the quantity delivered, `0` for a line not delivered. Each one is paired with a line of the Payment by your own `externalId` first and, when it is not sent, by product: `productId`, then `product.externalId`, then product name and variant, in any order; lines sharing the same product are paired in order of appearance, which is why sending your own `externalId` is the safe choice.
     * Prices stay the ones ordered, a discount on the whole line follows the quantity, and the discounts of the Payment are applied to the amount delivered. The one way a line can cost more than ordered is `pieces`, on a line created with `product.unit`: their amounts replace the ordered price, within what the `PreauthorizationMargin` line authorised. The line keeps what was ordered and carries the result in `confirmedQuantity`, `confirmedFinalAmount` and `confirmedPieces`. A line missing or matching no line of the Payment fails with `LINES_MISMATCH`; a quantity above the ordered one fails with `QUANTITY_EXCEEDS_ORDERED`. The `PreauthorizationMargin` line is the exception: leave it out, or send it with quantity `0`.
     *
     * @var null|list<ConfirmPaymentRequestLine>
     */
    public ?array $lines;

    /**
     * The amount to confirm, in cents. It only drives what is captured, voided and given back on the transactions. Requires `foodAmount`.
     */
    public ?int $finalAmount;

    /**
     * The part of `finalAmount` that meal vouchers can pay, in cents. Requires `finalAmount`, and can't be higher than it.
     */
    public ?int $foodAmount;

    /**
     * One of the {@see RefundMode} constants.
     */
    public ?string $refundMode;

    protected static function types(): array
    {
        return [
            'transactions' => [ConfirmPaymentRequestTransaction::class],
            'lines' => [ConfirmPaymentRequestLine::class],
        ];
    }
}
