<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Request body for updating a Company. Only the fields passed change.
 */
class UpdateCompanyRequest extends VoucherlyObject
{
    /**
     * Company name.
     */
    public string $name;

    /**
     * A unique 6-character code that can be used to join or reference this Company. Another Company with the same code answers 409.
     */
    public string $joinCode;

    /**
     * The ID of the Company in your own system. Null removes it. Another Company with the same reference answers 409.
     */
    public ?string $referenceId;
}
