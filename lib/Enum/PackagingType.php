<?php

namespace VoucherlyApi\Enum;

/**
 * The packaging used by the Customers of this Company that do not set one of their own. Null when the Company follows the ecommerce site default.
 */
final class PackagingType
{
    public const REUSABLE = 'Reusable';

    public const DISPOSABLE = 'Disposable';
}
