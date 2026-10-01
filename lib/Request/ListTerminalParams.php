<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\TerminalStatus;

final class ListTerminalParams extends RequestParams
{
    /**
     * Filter by the PaymentGatewayAccount the Terminal belongs to.
     */
    public ?string $paymentGatewayAccountId;

    /**
     * Filter by the PaymentGateway the Terminal belongs to.
     */
    public ?string $paymentGatewayId;

    /**
     * Filter by the Store the Terminal is bound to.
     */
    public ?string $storeId;

    /**
     * Filter by Terminal status. When omitted, deleted Terminals are excluded.
     * One of the {@see TerminalStatus} constants.
     */
    public ?string $status;

    /**
     * A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10.
     */
    public ?int $length;

    /**
     * A cursor for pagination across multiple pages of results. Don’t include this parameter on the first call. Use the `nextStart` value returned in a previous response to request subsequent results.
     */
    public ?string $start;
}
