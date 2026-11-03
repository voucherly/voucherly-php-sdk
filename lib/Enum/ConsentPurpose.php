<?php

namespace VoucherlyApi\Enum;

/**
 * What the consent is for. `Marketing` is receiving promotional messages; `Profiling` is the analysis of the purchase history to tailor offers.
 */
final class ConsentPurpose
{
    public const MARKETING = 'Marketing';

    public const PROFILING = 'Profiling';
}
