<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\PaymentMode;
use VoucherlyApi\Enum\PaymentStatus;
use VoucherlyApi\Enum\TenantMode;
use VoucherlyApi\VoucherlyObject;

/**
 * The Payment object.
 */
class Payment extends VoucherlyObject
{
    public ?string $id;

    /**
     * One of the {@see TenantMode} constants.
     */
    public ?string $tenant;

    /**
     * The ID of the merchant this Payment belongs to, that is your own merchant.
     */
    public ?string $merchantId;

    /**
     * A unique string to reference the Payment. This can be a customer ID, a cart ID, or similar, and can be used to reconcile the Payment with your internal systems.
     */
    public ?string $referenceId;

    /**
     * The identifier of the order this Payment settles in your own systems.
     */
    public ?string $externalOrderId;

    /**
     * One of the {@see PaymentMode} constants.
     */
    public ?string $mode;

    /**
     * The ID of the parent Payment, if this Payment is a child payment (e.g., a wallet).
     */
    public ?string $parentPaymentId;

    /**
     * The ID of the Company this Payment is associated with.
     */
    public ?string $companyId;

    /**
     * The ID of the Store this Payment belongs to.
     */
    public ?string $storeId;

    /**
     * The ID of the Customer of this Payment.
     */
    public ?string $customerId;

    /**
     * The email address of the customer associated with this Payment. Required when `customerId` is not provided.
     */
    public ?string $customerEmail;

    /**
     * The first name of the customer associated with this Payment.
     */
    public ?string $customerFirstName;

    /**
     * The last name of the customer associated with this Payment.
     */
    public ?string $customerLastName;

    /**
     * The phone number of the customer associated with this Payment.
     */
    public ?string $customerPhoneNumber;

    /**
     * The ID of the PaymentGateway preselected for the first transaction.
     */
    public ?string $selectedPaymentGateway;

    /**
     * The ID of the PaymentGatewayAccount used to process this Payment.
     */
    public ?string $paymentGatewayConfigurationId;

    /**
     * The URL where the customer should be redirected to complete the payment. This URL is provided after creating a Payment and should be used to redirect the customer to the Voucherly checkout page.
     */
    public ?string $checkoutUrl;

    /**
     * The URL Voucherly calls server to server when the Payment changes status.
     */
    public ?string $callbackUrl;

    public ?PaymentCloseCheckout $closeCheckout;

    public ?PaymentLastCallback $callback;

    /**
     * The total amount of the payment before discounts, in cents. This is the sum of all line items.
     */
    public ?int $totalAmount;

    /**
     * The total amount of discounts applied to the payment, in cents. This is the sum of all discount amounts.
     */
    public ?int $discountAmount;

    /**
     * The final amount to be paid after applying all discounts, in cents. This is calculated as totalAmount - discountAmount.
     */
    public ?int $finalAmount;

    /**
     * The part of finalAmount that meal vouchers can pay, in cents. It is the sum of the food lines after their discounts, capped at finalAmount.
     */
    public ?int $foodAmount;

    /**
     * The total amount that has been paid so far, in cents. This includes both regular payments and voucher payments.
     */
    public ?int $paidAmount;

    /**
     * The amount that has been paid with a digital payment method, in cents.
     */
    public ?int $paidDigitalAmount;

    /**
     * The amount that has been paid using vouchers, in cents.
     */
    public ?int $paidVoucherAmount;

    /**
     * The amount that has been paid with a fringe benefit credit, in cents.
     */
    public ?int $paidFringeAmount;

    /**
     * The amount that has been paid in cash, in cents.
     */
    public ?int $paidCashAmount;

    /**
     * The remaining amount to be paid, in cents. This is calculated as finalAmount - paidAmount.
     */
    public ?int $amount;

    /**
     * One of the {@see PaymentStatus} constants.
     */
    public ?string $status;

    /**
     * The amount that has been confirmed (captured), in cents.
     */
    public ?int $confirmedAmount;

    /**
     * The date and time the Payment was confirmed, in UTC.
     */
    public ?string $confirmedOnUtc;

    /**
     * The amount that has been cancelled, in cents.
     */
    public ?int $cancelledAmount;

    /**
     * The amount that has been refunded, in cents.
     */
    public ?int $refundedAmount;

    /**
     * The date and time the Payment was refunded, in UTC.
     */
    public ?string $refundedOnUtc;

    /**
     * The ID of the Receipt issued for this Payment.
     */
    public ?string $receiptId;

    /**
     * The ID of the Receipt issued for the refund of this Payment.
     */
    public ?string $refundReceiptId;

    /**
     * The Receipt issued for this Payment.
     */
    public ?Receipt $receipt;

    /**
     * The Receipt issued for the refund of this Payment.
     */
    public ?Receipt $refundReceipt;

    /**
     * The date and time the Payment was created, in UTC.
     */
    public ?string $created;

    /**
     * @var null|array<string, string>
     */
    public ?array $metadata;

    /**
     * An array of Transaction objects representing all payment attempts and transactions associated with this Payment.
     *
     * @var null|list<Transaction>
     */
    public ?array $transactions;

    /**
     * An array of PaymentDiscount objects representing all discounts applied to this Payment.
     *
     * @var null|list<PaymentDiscount>
     */
    public ?array $discounts;

    /**
     * An array of PaymentLine objects representing the line items (products or services) included in this Payment.
     *
     * @var null|list<PaymentLine>
     */
    public ?array $lines;

    protected static function types(): array
    {
        return [
            'closeCheckout' => PaymentCloseCheckout::class,
            'callback' => PaymentLastCallback::class,
            'receipt' => Receipt::class,
            'refundReceipt' => Receipt::class,
            'metadata' => 'map',
            'transactions' => [Transaction::class],
            'discounts' => [PaymentDiscount::class],
            'lines' => [PaymentLine::class],
        ];
    }
}
