<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

class PaymentDiscountRequest extends VoucherlyObject
{
    /**
     * The discount’s name, meant to be displayable to the customer. Not allowed with `couponCode`.
     */
    public string $discountName;

    /**
     * The discount’s description. Use this field to optionally store a long form explanation of the discount for your own rendering purposes. Not allowed with `couponCode`.
     */
    public ?string $discountDescription;

    /**
     * A positive integer in cents taken off the subtotal, the sum of the lines after their own discounts. Voucherly spreads it on every line except gifts, in proportion to their amount, to split it by VAT rate on the receipt and between food and non-food. Required unless the discount carries a `couponCode`.
     */
    public ?int $amount;

    /**
     * The code of a coupon created in Voucherly, as the customer typed it: case and spaces do not count. A discount with a `couponCode` takes no other field: Voucherly names the discount after the coupon and prices it by its rules. A coupon on products, a gift or the delivery becomes a discount of `level` `Line`, taken off the `discountAmount` of the lines it applies to; a coupon on the whole order becomes a discount of `level` `Subtotal`. Up to 5 discounts of a Payment can carry a code. A code that does not apply stops the Payment with a 400 whose `parameter` is `Discounts`, whose `couponCode` is the code and whose `code` says why: `COUPON_NOT_FOUND`, `COUPON_NOT_ACTIVE`, `COUPON_NOT_YET_VALID`, `COUPON_EXPIRED`, `COUPON_MIN_ORDER` (with `minOrderAmount`), `COUPON_NOT_APPLICABLE`, `COUPON_EXHAUSTED`, `COUPON_ALREADY_USED`, `COUPON_REQUIRES_CUSTOMER`, `COUPON_NOT_COMBINABLE`, `COUPON_NOT_AVAILABLE_AT_TABLE`, `COUPON_FIRST_ORDER_ONLY`.
     */
    public ?string $couponCode;
}
