<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * The writable fields of a Store Area.
 */
class StoreAreaRequest extends VoucherlyObject
{
    /**
     * The display name of the Store Area, unique across the Merchant. Leading and trailing whitespace is removed.
     * Required.
     */
    public string $name;
}
