<?php

namespace VoucherlyApi\Request;

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
     * Indicates whether the refunded amount should be added to the customer's wallet as credit.
     */
    public ?bool $asCredit;
}
