<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Aggregated spending history of the Customer.
 */
class SpendData extends VoucherlyObject
{
    /**
     * The total amount the Customer has spent, in cents.
     */
    public ?int $totalSpentAmount;

    /**
     * The total amount refunded to the Customer, in cents.
     */
    public ?int $totalRefundedAmount;

    /**
     * The number of Payments the Customer has completed.
     */
    public ?int $noOfPayments;

    /**
     * The average amount of the Customer's Payments, in cents.
     */
    public ?int $averagePaymentAmount;

    /**
     * The date and time of the Customer's first Payment, in UTC. The property name carries a typo that is part of the wire format.
     */
    public ?string $firstPaymentOnOtc;

    /**
     * The date and time of the Customer's most recent Payment, in UTC.
     */
    public ?string $lastPaymentOnUtc;
}
