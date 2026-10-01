<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\PackagingType;
use VoucherlyApi\VoucherlyObject;

/**
 * The packaging set on the Customer itself, for merchants that operate a reusable container scheme. Absent when the Customer inherits both the type and the deposit.
 */
class Packaging extends VoucherlyObject
{
    /**
     * Whether the Customer is on reusable or disposable packaging. Null when the Customer inherits the type from its Company, or from the ecommerce site default.
     * One of the {@see PackagingType} constants.
     */
    public ?string $type;

    /**
     * Whether the deposit on the reusable packaging has been paid.
     */
    public ?bool $isDepositPaid;
}
