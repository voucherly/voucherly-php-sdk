<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Optional settings for customer creation.
 */
class CreateCustomerRequestOptions extends VoucherlyObject
{
    /**
     * If true, the API will return an error if a customer with the same email address already exists. If false, allows duplicate emails.
     * Required.
     */
    public ?bool $requireUniqueEmail;
}
