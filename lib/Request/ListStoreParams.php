<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\StoreInclude;

final class ListStoreParams extends RequestParams
{
    /**
     * Filter by Stores whose name contains this value. The match is case-sensitive.
     */
    public ?string $name;

    /**
     * Filter by Store management status. When omitted, both active and inactive Stores are returned.
     */
    public ?bool $isActive;

    /**
     * Filter by the Stores assigned to this Concept Store.
     */
    public ?string $conceptStoreId;

    /**
     * Filter by the Stores assigned to this Store Area.
     */
    public ?string $storeAreaId;

    /**
     * Related data to embed in each Store. Omit to receive only the Store's own fields.
     * Each item is one of the {@see StoreInclude} constants.
     *
     * @var null|list<string>
     */
    public ?array $include;

    /**
     * A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10.
     */
    public ?int $length;

    /**
     * A cursor for pagination across multiple pages of results. Don’t include this parameter on the first call. Use the `nextStart` value returned in a previous response to request subsequent results.
     */
    public ?string $start;
}
