<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\CheckoutAction;
use VoucherlyApi\VoucherlyObject;

/**
 * Information about the payment request, including the action required and any redirect URL. The remaining properties depend on the PaymentGateway named by `$type`.
 */
class CheckoutPaymentRequest extends VoucherlyObject
{
    /**
     * The identifier of the PaymentGateway that produced the request, such as `stripe` or `satispay`. It tells you which gateway specific properties to expect alongside the documented ones.
     */
    public ?string $type;

    /**
     * The type of checkout action required to complete the payment.
     * One of the {@see CheckoutAction} constants.
     */
    public ?string $action;

    /**
     * The unique identifier of the transaction in the external payment gateway system.
     */
    public ?string $externalTransactionId;

    /**
     * The URL to redirect the customer to, if the action is REDIRECT. This URL is provided by the payment gateway.
     */
    public ?string $url;

    protected static function jsonNames(): array
    {
        return ['type' => '$type'];
    }
}
