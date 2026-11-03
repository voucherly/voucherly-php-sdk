<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * A volumes report row, aggregating turnover for a single PaymentGateway (and, when grouped by Store and/or Company, a single Store and/or Company).
 */
class RevenuesDetails extends VoucherlyObject
{
    /**
     * Unique identifier of the PaymentGateway this row aggregates.
     */
    public ?string $paymentGatewayId;

    /**
     * Display name of the PaymentGateway.
     */
    public ?string $paymentGatewayName;

    /**
     * Unique identifier of the Store this row aggregates. Only populated when `groupBy` includes `Store`.
     */
    public ?string $storeId;

    /**
     * Display name of the Store. Only populated when `groupBy` includes `Store`.
     */
    public ?string $storeName;

    /**
     * The first external identifier of the Store, as you set it. Only populated when `groupBy` includes `Store`.
     */
    public ?string $storeExternalId1;

    /**
     * The second external identifier of the Store, as you set it. Only populated when `groupBy` includes `Store`.
     */
    public ?string $storeExternalId2;

    /**
     * Unique identifier of the Company this row aggregates. Only populated when `groupBy` includes `Company`.
     */
    public ?string $companyId;

    /**
     * Display name of the Company. Only populated when `groupBy` includes `Company`.
     */
    public ?string $companyName;

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

    /**
     * Meal-voucher share of the confirmed amount, in cents. On a partial capture the missing amount is taken from the meal-voucher share last. For the `WALLET` PaymentGateway it is the customer credit originally funded by meal vouchers.
     */
    public ?int $confirmedVoucherAmount;

    /**
     * Number of meal vouchers confirmed. Transactions whose PaymentGateway does not report the count are left out and counted in `confirmedVoucherCountMissing`. Always `0` for the `WALLET` PaymentGateway, whose vouchers are counted when the credit is topped up.
     */
    public ?int $confirmedVoucherCount;

    /**
     * Number of transactions with a confirmed meal-voucher share but no voucher count, either because the PaymentGateway did not report it or because only part of the share was captured. When it is not `0`, `confirmedVoucherCount` is a lower bound.
     */
    public ?int $confirmedVoucherCountMissing;

    /**
     * Meal-voucher share refunded to the PaymentGateway, in cents. The refund takes the non-voucher share first. A refund kept as customer credit is not included, since it never reaches the voucher issuer.
     */
    public ?int $refundedVoucherAmount;

    /**
     * Number of meal vouchers refunded. Only a refund of the whole meal-voucher share carries a count; the others are counted in `refundedVoucherCountMissing`.
     */
    public ?int $refundedVoucherCount;

    /**
     * Number of transactions with a refunded meal-voucher share but no voucher count. When it is not `0`, `refundedVoucherCount` is a lower bound.
     */
    public ?int $refundedVoucherCountMissing;

    /**
     * Breakdown of this row by secondary (child) PaymentGateway, when applicable.
     *
     * @var null|list<ChildRevenuesDetails>
     */
    public ?array $childPaymentGateways;

    protected static function types(): array
    {
        return [
            'childPaymentGateways' => [ChildRevenuesDetails::class],
        ];
    }
}
