<?php

namespace VoucherlyApi\Enum;

/**
 * The type of payment gateway.
 */
final class PaymentGatewayType
{
    /**
     * A meal voucher payment gateway, such as Edenred or Pluxee.
     */
    public const MEAL_VOUCHER = 'MealVoucher';

    /**
     * A card payment gateway, for credit and debit cards.
     */
    public const CC = 'CC';

    /**
     * Any other payment gateway, such as Klarna or Apple Pay and Google Pay.
     */
    public const OTHER = 'Other';

    /**
     * A payment method of Voucherly itself, such as the wallet or the prepaid balance.
     */
    public const CUSTOM = 'Custom';

    /**
     * A payment gateway that is never offered at checkout, such as manual payments.
     */
    public const HIDDEN = 'Hidden';
}
