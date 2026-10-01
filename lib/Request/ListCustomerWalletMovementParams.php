<?php

namespace VoucherlyApi\Request;

final class ListCustomerWalletMovementParams extends RequestParams
{
    /**
     * Lower bound (inclusive) of the movement timestamp filter, in UTC.
     */
    public ?\DateTimeInterface $fromDate;

    /**
     * Upper bound (inclusive) of the movement timestamp filter, in UTC.
     */
    public ?\DateTimeInterface $toDate;

    /**
     * A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10.
     */
    public ?int $length;

    /**
     * A cursor for pagination across multiple pages of results. Don’t include this parameter on the first call. Use the `nextStart` value returned in a previous response to request subsequent results.
     */
    public ?string $start;
}
