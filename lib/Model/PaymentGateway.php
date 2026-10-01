<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\CheckoutAction;
use VoucherlyApi\Enum\PaymentGatewayType;
use VoucherlyApi\VoucherlyObject;

/**
 * Detailed information about a payment gateway, including merchant configuration and parameters.
 */
class PaymentGateway extends VoucherlyObject
{
    /**
     * Unique identifier for the payment gateway.
     */
    public ?string $id;

    /**
     * The display name of the payment gateway (e.g., "Visa", "Mastercard", "Edenred").
     */
    public ?string $name;

    /**
     * The type of payment gateway.
     * One of the {@see PaymentGatewayType} constants.
     */
    public ?string $type;

    /**
     * The type of checkout action required by this payment gateway.
     * One of the {@see CheckoutAction} constants.
     */
    public ?string $paymentAction;

    /**
     * URL of the image to display for this payment gateway.
     */
    public ?string $image;

    /**
     * URL of the image to display for this payment gateway in the checkout page.
     */
    public ?string $checkoutImage;

    /**
     * URL of the icon to display for this payment gateway.
     */
    public ?string $icon;

    /**
     * Indicates whether this payment gateway is currently active and available for use.
     */
    public ?bool $isActive;

    /**
     * Merchant-specific configuration for this payment gateway.
     */
    public ?MerchantPaymentGateway $merchantConfiguration;

    /**
     * An array of configuration parameters for this payment gateway.
     *
     * @var null|list<PaymentGatewayParameter>
     */
    public ?array $parameters;

    protected static function types(): array
    {
        return [
            'merchantConfiguration' => MerchantPaymentGateway::class,
            'parameters' => [PaymentGatewayParameter::class],
        ];
    }
}
