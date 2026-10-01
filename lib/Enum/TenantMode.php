<?php

namespace VoucherlyApi\Enum;

/**
 * Has the value "live" if the object exists in live mode, or "sand" if it exists in test mode.
 */
final class TenantMode
{
    public const SAND = 'sand';

    public const LIVE = 'live';
}
