<?php

namespace VoucherlyApi\Enum;

/**
 * The type of error returned.
 */
final class TransactionErrorCode
{
    /**
     * The PaymentGateway refused the Transaction without a code Voucherly maps.
     */
    public const GENERIC = 'Generic';

    /**
     * The payer abandoned the Transaction.
     */
    public const CANCELLED = 'Cancelled';

    /**
     * The Transaction needs a PaymentMethod and none was supplied.
     */
    public const MISSING_PAYMENT_METHOD = 'MissingPaymentMethod';

    /**
     * The PaymentGateway declined the Transaction; `declineErrorCode` says why, when it says.
     */
    public const DECLINED = 'Declined';

    /**
     * The PaymentGateway accepted the Transaction but left it pending its own review.
     */
    public const UNCLEARED = 'Uncleared';

    /**
     * Strong customer authentication did not complete.
     */
    public const AUTHENTICATION_FAILED = 'AuthenticationFailed';

    /**
     * The requested amount is above what the meal vouchers presented can cover.
     */
    public const EXCEED_VOUCHER_AMOUNT = 'ExceedVoucherAmount';

    /**
     * The card present Terminal did not answer.
     */
    public const TERMINAL_OFFLINE = 'TerminalOffline';

    /**
     * No Terminal matches the one the Transaction asked for.
     */
    public const TERMINAL_NOT_FOUND = 'TerminalNotFound';

    /**
     * The Terminal is already running another Transaction.
     */
    public const TERMINAL_BUSY = 'TerminalBusy';

    /**
     * The PaymentGateway account of the merchant is not configured for this Transaction.
     */
    public const MERCHANT_CONFIGURATION = 'MerchantConfiguration';

    /**
     * Voucherly failed to complete the Transaction.
     */
    public const SYSTEM = 'System';
}
