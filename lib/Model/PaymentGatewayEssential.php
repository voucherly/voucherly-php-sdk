<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\CheckoutAction;
use VoucherlyApi\Enum\PaymentGatewayType;
use VoucherlyApi\VoucherlyObject;

/**
 * Essential information about a PaymentGateway, used when embedded in other resources (e.g., Transactions, Terminals).
 */
class PaymentGatewayEssential extends VoucherlyObject
{
    /**
     * Unique identifier for the PaymentGateway.
     */
    public ?string $id;

    /**
     * The display name of the PaymentGateway (e.g., "Visa", "Mastercard", "Edenred").
     */
    public ?string $name;

    /**
     * URL of the image to display for this PaymentGateway.
     */
    public ?string $image;

    /**
     * Null when the PaymentGateway is no longer offered and only survives on historical records.
     * One of the {@see PaymentGatewayType} constants.
     */
    public ?string $type;

    /**
     * Null when the PaymentGateway is no longer offered and only survives on historical records.
     * One of the {@see CheckoutAction} constants.
     */
    public ?string $paymentAction;
}
