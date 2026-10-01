<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * A Store Area is a geographical or operational grouping of Stores, defined by the Merchant.
 */
class StoreArea extends VoucherlyObject
{
    /**
     * Unique identifier for the Store Area.
     */
    public ?string $id;

    /**
     * The display name of the Store Area, unique across the Merchant.
     */
    public ?string $name;

    /**
     * The UTC timestamp when the Store Area was created.
     */
    public ?string $createdOnUtc;
}
