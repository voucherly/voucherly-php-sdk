<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\DiscountType;
use VoucherlyApi\VoucherlyObject;

class PaymentDiscount extends VoucherlyObject
{
    /**
     * The discount’s name, meant to be displayable to the customer.
     */
    public ?string $discountName;

    /**
     * The discount’s description. Use this field to optionally store a long form explanation of the product being sold for your own rendering purposes.
     */
    public ?string $discountDescription;

    /**
     * A non-negative integer in cents representing how much to subtract from the total. Must be specified when both `type` and `value` are null.
     */
    public ?int $amount;

    /**
     * One of the {@see DiscountType} constants.
     */
    public ?string $type;

    /**
     * The value of the discount, contextual to the selected `type`. Must be specified when `type` is provided and `amount` is null.
     */
    public ?int $value;

    /**
     * Defines the application order of this discount relative to others.
     */
    public ?int $index;

    /**
     * Primary external reference for the discount, used by Voucherly to reconcile it across systems and to correctly compute reporting.
     */
    public ?string $externalId1;

    /**
     * Secondary external reference for the discount, used by Voucherly to reconcile it across systems and to correctly compute reporting.
     */
    public ?string $externalId2;

    /**
     * The coupon code the payer entered to obtain this discount.
     */
    public ?string $couponCode;

    /**
     * The loyalty points spent to obtain this discount.
     */
    public ?int $points;
}
