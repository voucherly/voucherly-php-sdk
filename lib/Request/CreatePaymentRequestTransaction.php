<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * A transaction prepared on a specific payment gateway.
 */
class CreatePaymentRequestTransaction extends VoucherlyObject
{
    /**
     * The id of the payment gateway that will process this transaction.
     */
    public string $paymentGatewayId;

    /**
     * The amount of this transaction, in cents. When omitted the whole Payment amount is used.
     */
    public ?int $amount;

    /**
     * Honored only by the `Wallet` and `Prepaid` payment gateways: every other gateway ignores it. When true and the customer's balance does not cover the amount of this transaction, the transaction is declined instead of drawing what is available. The Payment is created anyway, and the response is a 422 that carries it. When false, the transaction draws as much of the balance as the Payment still needs, and `completionMode` decides whether the Payment stays open for the rest.
     */
    public ?bool $requireSingleTransactionForWholePayment;

    /**
     * Gateway-specific details of the transaction. Only `POS` takes them today, to say which card reader must collect the payment: give exactly one of the three identifiers below. The Terminal is looked up across the whole merchant, so it must be paired and, for `referenceTerminalId`, carry that reference — otherwise the request fails with `RESOURCE_MISSING` and no Payment is created.
     */
    public ?CreatePaymentRequestTransactionDetails $details;

    protected static function types(): array
    {
        return [
            'details' => CreatePaymentRequestTransactionDetails::class,
        ];
    }
}
