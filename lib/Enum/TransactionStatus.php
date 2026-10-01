<?php

namespace VoucherlyApi\Enum;

/**
 * Current status of the Transaction.
 */
final class TransactionStatus
{
    public const REQUEST_FAILED = 'RequestFailed';

    public const REQUESTED = 'Requested';

    public const PAID = 'Paid';

    public const CONFIRMED = 'Confirmed';

    public const REFUNDED = 'Refunded';

    public const REFUNDED_PARTIALLY = 'RefundedPartially';

    public const CANCELLED = 'Cancelled';

    public const FAILED = 'Failed';

    public const VOIDED = 'Voided';

    public const EXPIRED = 'Expired';

    public const REVERSED = 'Reversed';

    public const IMPOSSIBLE_REFUND = 'ImpossibleRefund';

    public const NEXT_ACTION = 'NextAction';
}
