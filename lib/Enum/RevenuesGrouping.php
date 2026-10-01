<?php

namespace VoucherlyApi\Enum;

/**
 * A grouping dimension for volumes report rows. `Store` breaks the rows down per Store; `Company` breaks them down per Company. With no dimension at all, rows are aggregated per PaymentGateway only.
 */
final class RevenuesGrouping
{
    public const STORE = 'Store';

    public const COMPANY = 'Company';
}
