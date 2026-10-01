<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * A secondary (child) PaymentGateway breakdown nested inside a volumes report row.
 */
class ChildRevenuesDetails extends VoucherlyObject
{
    /**
     * Unique identifier of the child PaymentGateway.
     */
    public ?string $paymentGatewayId;

    /**
     * Display name of the child PaymentGateway.
     */
    public ?string $paymentGatewayName;

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
