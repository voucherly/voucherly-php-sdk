<?php

namespace VoucherlyApi\Enum;

/**
 * The current health of the Store.
 */
final class StoreStatusValue
{
    public const UP = 'Up';

    public const DEGRADED = 'Degraded';

    public const DOWN = 'Down';
}
