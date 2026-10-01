<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * The prepaid policy that the Customer is subject to.
 */
class PrepaidPolicy extends VoucherlyObject
{
    /**
     * Indicates whether the prepaid policy is currently active.
     */
    public ?bool $isActive;

    /**
     * The maximum amount, in cents, that the Customer is allowed to spend in a single Date under this policy.
     */
    public ?int $amount;
}
