<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\RefundMode;
use VoucherlyApi\VoucherlyObject;

class RefundPaymentRequestTransaction extends VoucherlyObject
{
    /**
     * The id of the transaction.
     */
    public ?string $id;

    /**
     * If specified, is the partial amount to be refunded.
     */
    public ?int $amount;

    /**
     * How this transaction is given back, when the request sets no `refundMode` of its own. Defaults to `Gateway`; `NoRefund` fails with `VALIDATION_ERROR`.
     * One of the {@see RefundMode} constants.
     */
    public ?string $refundMode;
}
