<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\CustomerInclude;

final class RetrieveCustomerParams extends RequestParams
{
    /**
     * An array of nested object to be included in response.
     * Each item is one of the {@see CustomerInclude} constants.
     *
     * @var null|list<string>
     */
    public ?array $include;
}
