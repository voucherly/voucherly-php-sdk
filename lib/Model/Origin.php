<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\CustomerOriginType;
use VoucherlyApi\VoucherlyObject;

/**
 * Where the Customer was won, set when it was created and never changed. Null for the Customers created before it was recorded.
 */
class Origin extends VoucherlyObject
{
    /**
     * The point of acquisition. `Api` is a Customer your own system created without saying where it came from.
     * One of the {@see CustomerOriginType} constants.
     */
    public ?string $type;

    /**
     * The Store where the Customer was won, such as the Store of the table or of the reservation.
     */
    public ?string $storeId;

    /**
     * The ID of what brought the Customer, such as the Payment link or the reservation.
     */
    public ?string $sourceId;
}
