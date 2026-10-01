<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * If this is a sepa_debit PaymentMethod, this contains the user’s card details.
 */
class DirectDebitInfo extends VoucherlyObject
{
    /**
     * ID of the bank account.
     */
    public ?string $accountId;

    /**
     * BIC of the bank account.
     */
    public ?string $accountBic;

    /**
     * IBAN of the bank account.
     */
    public ?string $accountIban;
}
