<?php

namespace VoucherlyApi\Enum;

/**
 * The point of acquisition. `Api` is a Customer your own system created without saying where it came from.
 */
final class CustomerOriginType
{
    public const SITE = 'Site';

    public const TABLE = 'Table';

    public const PAYMENT_LINK = 'PaymentLink';

    public const RESERVATION = 'Reservation';

    public const LOYALTY_CARD = 'LoyaltyCard';

    public const COLLABORATOR = 'Collaborator';

    public const POPUP = 'Popup';

    public const IMPORT = 'Import';

    public const STAFF = 'Staff';

    public const API = 'Api';
}
