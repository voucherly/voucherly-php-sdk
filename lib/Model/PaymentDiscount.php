<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\DiscountLevel;
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
     * `Line` when the discount is already in the `discountAmount` of the lines it applies to, `Subtotal` when it comes off the sum of the lines and leaves them as they are.
     * One of the {@see DiscountLevel} constants.
     */
    public ?string $level;

    /**
     * In cents, what the discount is worth. A discount of `level` `Subtotal` comes off the sum of the lines; one of `level` `Line` is already in their `discountAmount` and is not taken off again.
     */
    public ?int $amount;

    /**
     * Primary external reference for the discount, used by Voucherly to reconcile it across systems and to correctly compute reporting.
     */
    public ?string $externalId1;

    /**
     * Secondary external reference for the discount, used by Voucherly to reconcile it across systems and to correctly compute reporting.
     */
    public ?string $externalId2;

    /**
     * The code of the Voucherly coupon that produced this discount, as passed in the `couponCode` of a discount of the request.
     */
    public ?string $couponCode;

    /**
     * The code of a promotion priced by the merchant's POS, such as one the customer entered at the table. Voucherly passes it back to the POS and does not validate it.
     */
    public ?string $externalCouponCode;

    /**
     * The loyalty points spent to obtain this discount.
     */
    public ?int $points;
}
