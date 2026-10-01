<?php

namespace VoucherlyApi\Enum;

/**
 * Related data that can be embedded in a Store response through the `include` parameter.
 */
final class StoreInclude
{
    public const CONCEPT_STORE = 'conceptStore';

    public const STORE_AREA = 'storeArea';

    public const STATUS = 'status';
}
