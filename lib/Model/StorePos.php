<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * The connection to the POS system that serves the Store. Setting it makes `externalId1` required.
 */
class StorePos extends VoucherlyObject
{
    /**
     * The identifier of the Store inside the POS system.
     */
    public ?string $posId;

    /**
     * The base URL of the POS system. A trailing slash is added when missing.
     */
    public ?string $posEndpoint;
}
