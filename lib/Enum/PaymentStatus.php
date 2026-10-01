<?php

namespace VoucherlyApi\Enum;

/**
 * Current status of the Payment.
 */
final class PaymentStatus
{
    public const REQUESTED = 'Requested';

    public const PAID = 'Paid';

    public const CONFIRMED = 'Confirmed';

    public const REFUNDED = 'Refunded';

    public const CANCELLED = 'Cancelled';

    public const VOIDED = 'Voided';

    public const EXPIRED = 'Expired';
}
