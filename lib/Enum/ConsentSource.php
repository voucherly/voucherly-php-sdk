<?php

namespace VoucherlyApi\Enum;

/**
 * Where the choice was collected. Defaults to `Other`.
 */
final class ConsentSource
{
    public const SIGNUP = 'Signup';

    public const PROFILE = 'Profile';

    public const CHECKOUT = 'Checkout';

    public const RESERVATION = 'Reservation';

    public const LIVE_CAPTURE = 'LiveCapture';

    public const IMPORT = 'Import';

    public const STAFF = 'Staff';

    public const OTHER = 'Other';
}
