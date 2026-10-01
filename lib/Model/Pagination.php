<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

class Pagination extends VoucherlyObject
{
    /**
     * Whether or not there are more elements available after this set. If false, this set comprises the end of the list.
     */
    public ?bool $hasMore;

    /**
     * A cursor for use in pagination. If `hasMore` is true, you can pass the value of `nextStart` to a subsequent call to fetch the next page of results.
     */
    public ?string $nextStart;
}
