<?php

namespace VoucherlyApi\Enum;

/**
 * The type of checkout action required by the payment gateway.
 */
final class CheckoutAction
{
    /**
     * No interaction is required. The payment is charged server to server.
     */
    public const DIRECT = 'DIRECT';

    /**
     * One-Time Password authentication is required. The customer needs to enter an OTP code.
     */
    public const OTP = 'OTP';

    /**
     * A One-Time Password is required together with the italian fiscal code of the payer.
     */
    public const OTP_CF = 'OTP_CF';

    /**
     * The customer must be redirected to an external URL to complete the payment.
     */
    public const REDIRECT = 'REDIRECT';

    /**
     * An embedded payment form (drop-in) can be used within your application.
     */
    public const DROPIN = 'DROPIN';
}
