<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Summary information about the Customer's wallet, including total amounts and last update timestamp.
 */
class CustomerWalletTotals extends VoucherlyObject
{
    /**
     * The total amount available in the customer's wallet, in cents.
     */
    public ?int $totalAmount;

    /**
     * The total amount of voucher funds available in the customer's wallet, in cents.
     */
    public ?int $totalVoucherAmount;

    /**
     * The total amount of cash funds available in the customer's wallet, in cents.
     */
    public ?int $totalCashAmount;

    /**
     * The UTC timestamp of when the wallet totals were last updated.
     */
    public ?string $lastUpdatedOnUtc;
}
