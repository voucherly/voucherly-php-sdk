<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * What a Payment created from the same body would cost. Nothing is created.
 */
class PaymentQuote extends VoucherlyObject
{
    /**
     * In cents, the sum of the lines before any discount.
     */
    public ?int $totalAmount;

    /**
     * In cents, every discount, on the lines and on the whole Payment.
     */
    public ?int $discountAmount;

    /**
     * In cents, what the customer would pay.
     */
    public ?int $finalAmount;

    /**
     * In cents, the part of `finalAmount` that meal vouchers can pay.
     */
    public ?int $foodAmount;

    /**
     * @var null|list<PaymentLine>
     */
    public ?array $lines;

    /**
     * @var null|list<PaymentDiscount>
     */
    public ?array $discounts;

    protected static function types(): array
    {
        return [
            'lines' => [PaymentLine::class],
            'discounts' => [PaymentDiscount::class],
        ];
    }
}
