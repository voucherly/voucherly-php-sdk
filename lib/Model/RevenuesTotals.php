<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Grand totals of the volumes report, computed across the whole result set.
 */
class RevenuesTotals extends VoucherlyObject
{
    /**
     * Total amount of paid transactions, in cents.
     */
    public ?int $paidAmount;

    /**
     * Amount that was paid but not confirmed (paid minus confirmed), in cents.
     */
    public ?int $cancelledAmount;

    /**
     * Total confirmed amount, in cents.
     */
    public ?int $confirmedAmount;

    /**
     * Total refunded amount, in cents.
     */
    public ?int $refundedAmount;

    /**
     * Net revenue (confirmed minus refunded), in cents.
     */
    public ?int $profitAmount;

    /**
     * Number of paid transactions.
     */
    public ?int $paidCount;

    /**
     * Number of paid-but-not-confirmed transactions.
     */
    public ?int $cancelledCount;

    /**
     * Number of confirmed transactions.
     */
    public ?int $confirmedCount;

    /**
     * Number of refunded transactions.
     */
    public ?int $refundedCount;

    /**
     * Number of transactions contributing to net revenue.
     */
    public ?int $profitCount;
}
