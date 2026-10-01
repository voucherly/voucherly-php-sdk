<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\TransactionStatus;
use VoucherlyApi\VoucherlyObject;

/**
 * A Transaction represents a single payment attempt or operation within a Payment. A Payment can have multiple Transactions.
 */
class Transaction extends VoucherlyObject
{
    /**
     * Unique identifier for the transaction.
     */
    public ?string $id;

    /**
     * The ID of the PaymentGatewayAccount that processed this transaction.
     */
    public ?string $paymentGatewayAccountId;

    /**
     * The ID of the PaymentGateway used for this transaction.
     */
    public ?string $paymentGatewayId;

    /**
     * Essential information about the PaymentGateway used for this transaction.
     */
    public ?PaymentGatewayEssential $paymentGateway;

    /**
     * The ID of the secondary PaymentGateway, when the transaction rides on another gateway (e.g. a wallet on a card gateway).
     */
    public ?string $paymentGatewayId2;

    /**
     * The ID of the Terminal that took the payment, for card-present transactions.
     */
    public ?string $terminalId;

    /**
     * The primary external transaction ID from the payment gateway provider.
     */
    public ?string $externalId1;

    /**
     * An additional external transaction ID or reference from the payment gateway provider.
     */
    public ?string $externalId2;

    /**
     * A third external transaction ID or reference from the payment gateway provider.
     */
    public ?string $externalId3;

    /**
     * Error information if the transaction failed.
     */
    public ?TransactionError $error;

    /**
     * The date and time the transaction was requested, in UTC.
     */
    public ?string $requestedAt;

    /**
     * The amount that was requested for this transaction, in cents.
     */
    public ?int $requestedAmount;

    /**
     * The part of the requested amount the gateway could not charge because it sits below its minimum, in cents.
     */
    public ?int $exceedingAmount;

    /**
     * The actual amount processed in this transaction, in cents.
     */
    public ?int $amount;

    /**
     * The amount paid with a digital payment method in this transaction, in cents.
     */
    public ?int $digitalAmount;

    /**
     * The amount paid using vouchers in this transaction, in cents.
     */
    public ?int $voucherAmount;

    /**
     * The amount paid with a fringe benefit credit in this transaction, in cents.
     */
    public ?int $fringeAmount;

    /**
     * The amount paid in cash in this transaction, in cents.
     */
    public ?int $cashAmount;

    /**
     * The number of vouchers used in this transaction.
     */
    public ?int $noOfVouchers;

    /**
     * The amount that has been confirmed (captured) from this transaction, in cents.
     */
    public ?int $confirmedAmount;

    /**
     * The amount that has been refunded from this transaction, in cents.
     */
    public ?int $refundedAmount;

    /**
     * The date and time the transaction was paid, in UTC.
     */
    public ?string $paidOnUtc;

    /**
     * The currency code for this transaction (e.g., "EUR", "USD").
     */
    public ?string $currency;

    /**
     * Credit card information if this transaction was paid with a card.
     */
    public ?CreditCardInfo $creditCard;

    /**
     * Direct debit (SEPA) information if this transaction was paid via direct debit.
     */
    public ?DirectDebitInfo $directDebit;

    /**
     * The email address of the payment method holder for this transaction.
     */
    public ?string $holderEmail;

    /**
     * The name of the payment method holder for this transaction.
     */
    public ?string $holderName;

    /**
     * The current status of this transaction.
     * One of the {@see TransactionStatus} constants.
     */
    public ?string $status;

    /**
     * Information about the payment request, including any redirect URL or action required.
     */
    public ?CheckoutPaymentRequest $request;

    /**
     * The action the payer still has to perform for the transaction to complete.
     */
    public ?TransactionNextAction $nextAction;

    /**
     * The confirm, refund, cancel and reverse operations performed on this transaction.
     *
     * @var null|list<TransactionOperation>
     */
    public ?array $operations;

    protected static function types(): array
    {
        return [
            'paymentGateway' => PaymentGatewayEssential::class,
            'error' => TransactionError::class,
            'creditCard' => CreditCardInfo::class,
            'directDebit' => DirectDebitInfo::class,
            'request' => CheckoutPaymentRequest::class,
            'nextAction' => TransactionNextAction::class,
            'operations' => [TransactionOperation::class],
        ];
    }
}
