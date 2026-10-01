<?php

namespace VoucherlyApi\Enum;

/**
 * Where the PaymentLine comes from:
 * - `Standard`: the line was part of the original order.
 * - `TableUpsell`: the line was added by an upselling proposal accepted while paying at the table.
 */
final class LineOrigin
{
    public const STANDARD = 'Standard';

    public const TABLE_UPSELL = 'TableUpsell';
}
