<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\StoreInclude;

final class RetrieveStoreParams extends RequestParams
{
    /**
     * Related data to embed in the Store. Omit to receive only the Store's own fields.
     * Each item is one of the {@see StoreInclude} constants.
     *
     * @var null|list<string>
     */
    public ?array $include;
}
