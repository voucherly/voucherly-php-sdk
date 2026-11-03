<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Request body for creating a Company.
 */
class CreateCompanyRequest extends VoucherlyObject
{
    /**
     * A unique 6-character code that can be used to join or reference this Company.
     */
    public string $joinCode;

    /**
     * Company name.
     * Required.
     */
    public string $name;

    /**
     * The ID of the Company in your own system. Another Company with the same reference answers 409.
     */
    public ?string $referenceId;
}
