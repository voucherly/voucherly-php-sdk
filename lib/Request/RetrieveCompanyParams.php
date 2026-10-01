<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\CompanyInclude;

final class RetrieveCompanyParams extends RequestParams
{
    /**
     * An array of nested object to be included in response.
     * Each item is one of the {@see CompanyInclude} constants.
     *
     * @var null|list<string>
     */
    public ?array $include;
}
