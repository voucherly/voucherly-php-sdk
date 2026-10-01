<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * The PaymentMethod object.
 */
class PaymentMethod extends VoucherlyObject
{
    public ?string $id;

    /**
     * The ID of the Customer to which this PaymentMethod is saved.
     */
    public ?string $customerId;

    /**
     * The ID of the PaymentGatewayAccount that holds this PaymentMethod.
     */
    public ?string $paymentGatewayAccountId;

    /**
     * The ID of the PaymentGateway through which this PaymentMethod is processed.
     */
    public ?string $paymentGatewayId;

    /**
     * The ID of the PaymentMethod as registered in the external PaymentGateway system. Used to reference the method in operations with the provider.
     */
    public ?string $externalId;

    /**
     * Email address associated with the PaymentMethod holder. This may differ from the Customer's email.
     */
    public ?string $holderEmail;

    /**
     * Full name of the individual or entity that owns the PaymentMethod. This may differ from the Customer's registered name.
     */
    public ?string $holderName;

    public ?CreditCardInfo $creditCard;

    public ?DirectDebitInfo $directDebit;

    protected static function types(): array
    {
        return [
            'creditCard' => CreditCardInfo::class,
            'directDebit' => DirectDebitInfo::class,
        ];
    }
}
