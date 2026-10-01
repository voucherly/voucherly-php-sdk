<?php

namespace VoucherlyApi\Enum;

/**
 * Why the PaymentGateway declined the Transaction, when it told Voucherly. Only present on a `Declined` error.
 */
final class DeclineErrorCode
{
    public const GENERIC = 'Generic';

    public const INSUFFICIENT_FUNDS = 'InsufficientFunds';

    public const EXPIRED_CARD = 'ExpiredCard';

    public const CARD_LIMIT_EXCEEDED = 'CardLimitExceeded';

    public const SUSPECTED_FRAUD = 'SuspectedFraud';

    public const FRAUD = 'Fraud';

    public const STOLEN_OR_LOST_CARD = 'StolenOrLostCard';

    public const CLOSED_ACCOUNT = 'ClosedAccount';
}
