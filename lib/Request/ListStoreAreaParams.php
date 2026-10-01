<?php

namespace VoucherlyApi\Request;

final class ListStoreAreaParams extends RequestParams
{
    /**
     * Filter by Store Areas whose name contains this value. The match is case-sensitive.
     */
    public ?string $name;

    /**
     * Filter by a specific set of Store Area identifiers.
     *
     * @var null|list<string>
     */
    public ?array $ids;

    /**
     * A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10.
     */
    public ?int $length;

    /**
     * A cursor for pagination across multiple pages of results. Don’t include this parameter on the first call. Use the `nextStart` value returned in a previous response to request subsequent results.
     */
    public ?string $start;
}
