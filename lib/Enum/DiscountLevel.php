<?php

namespace VoucherlyApi\Enum;

/**
 * `Line` when the discount is already in the `discountAmount` of the lines it applies to, `Subtotal` when it comes off the sum of the lines and leaves them as they are.
 */
final class DiscountLevel
{
    public const LINE = 'Line';

    public const SUBTOTAL = 'Subtotal';
}
