<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

class RefundPaymentRequest extends VoucherlyObject
{
    /**
     * Indicates whether the refunded amount should be added to the customer's wallet as credit.
     */
    public ?bool $asCredit;

    /**
     * List of transactions to be refunded.
     *
     * @var null|list<RefundPaymentRequestTransaction>
     */
    public ?array $transactions;

    protected static function types(): array
    {
        return [
            'transactions' => [RefundPaymentRequestTransaction::class],
        ];
    }
}
