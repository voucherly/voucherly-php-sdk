<?php

namespace VoucherlyApi\Enum;

/**
 * Defines how the discount is calculated:
 * - `DOLLAROFF`: A fixed amount in cents is subtracted from the total.
 * - `PERCENOFF`: A percentage of the total amount is subtracted.
 * - `FIXED`: The final price is set to a fixed amount, overriding the original total.
 */
final class DiscountType
{
    public const DOLLAROFF = 'DOLLAROFF';

    public const PERCENOFF = 'PERCENOFF';

    public const FIXED = 'FIXED';
}
