<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

class ConfirmPaymentRequestTransaction extends VoucherlyObject
{
    /**
     * The id of the transaction.
     */
    public ?string $id;

    /**
     * If specified, is the partial amount to be confirmed.
     */
    public ?int $amount;
}
