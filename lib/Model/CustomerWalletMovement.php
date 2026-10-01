<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\CustomerWalletAction;
use VoucherlyApi\VoucherlyObject;

/**
 * A single movement recorded on the Customer's wallet.
 */
class CustomerWalletMovement extends VoucherlyObject
{
    /**
     * The ID of the Customer this movement belongs to.
     */
    public ?string $customerId;

    /**
     * One of the {@see CustomerWalletAction} constants.
     */
    public ?string $action;

    /**
     * The ID of the Transaction that originated this movement, when applicable.
     */
    public ?string $transactionId;

    /**
     * The total amount of the movement, in cents.
     */
    public ?int $amount;

    /**
     * The portion of `amount` attributable to vouchers, in cents.
     */
    public ?int $voucherAmount;

    /**
     * The portion of `amount` attributable to cash, in cents.
     */
    public ?int $cashAmount;

    /**
     * The wallet total balance after this movement was applied, in cents.
     */
    public ?int $runningTotalAmount;

    /**
     * The wallet voucher balance after this movement was applied, in cents.
     */
    public ?int $runningTotalVoucherAmount;

    /**
     * The wallet cash balance after this movement was applied, in cents.
     */
    public ?int $runningTotalCashAmount;

    /**
     * The UTC timestamp when this movement was recorded.
     */
    public ?string $occurredAtUtc;
}
