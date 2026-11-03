<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\RefundMode;
use VoucherlyApi\VoucherlyObject;

class RefundPaymentRequest extends VoucherlyObject
{
    /**
     * How every transaction is given back, overriding the `refundMode` of each one. Defaults to `Gateway`; `NoRefund` fails with `VALIDATION_ERROR`.
     * One of the {@see RefundMode} constants.
     */
    public ?string $refundMode;

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
