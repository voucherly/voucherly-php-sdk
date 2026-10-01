<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\CompletionMode;
use VoucherlyApi\Enum\ExceedingAmountMode;
use VoucherlyApi\Enum\PaymentMode;
use VoucherlyApi\Model\PaymentDiscount;
use VoucherlyApi\VoucherlyObject;

/**
 * Request body for creating a Payment.
 */
class CreatePaymentRequest extends VoucherlyObject
{
    /**
     * A unique string to reference the Payment. This can be a customer ID, a cart ID, or similar, and can be used to reconcile the Payment with your internal systems.
     */
    public ?string $referenceId;

    /**
     * The date this Payment refers to in your own system, when it differs from the date it is created on.
     */
    public ?\DateTimeInterface $referenceDate;

    /**
     * Required.
     * One of the {@see PaymentMode} constants.
     */
    public ?string $mode;

    /**
     * The ID of the Company this Payment is associated with. If empty, the Company associated with the Customer will be used, if one exists.
     * You cannot specify both `customerId` and `companyId` at the same time.
     */
    public ?string $companyId;

    /**
     * The ID of the Customer of this Payment. If empty a new Customer will be created.
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
     * The ID of the Customer's PaymentMethod to attach to this Payment.
     */
    public ?string $customerPaymentMethodId;

    /**
     * Default PaymentGateway used for the first transaction.
     */
    public ?string $selectedPaymentGatewayId;

    /**
     * The ID of the PaymentGatewayConfiguration to use for this Payment, in the form `pgc_01kg2gestgepdbmsn7hs6bsrwp`. Use it when the same gateway is configured more than once and you need a specific configuration. When omitted, the configuration is resolved in this order: the default configuration of the API key used for the request, then the one of the Store the Payment refers to, then the Merchant default configuration.
     */
    public ?string $paymentGatewayConfigurationId;

    /**
     * @var null|array<string, string>
     */
    public ?array $metadata;

    /**
     * An array of PaymentLine objects representing the line items (products or services) to include in this Payment. Each line must reference a product either by `productId`, by an inline `product` object, or both (in which case the inline `product` overrides the referenced Product configuration).
     *
     * @var null|list<PaymentLineRequest>
     */
    public ?array $lines;

    /**
     * An array of PaymentDiscount objects representing all discounts applied to this Payment.
     *
     * @var null|list<PaymentDiscount>
     */
    public ?array $discounts;

    /**
     * Seconds the customer has to complete the checkout, after which the Payment is voided. Without it the checkout has no deadline of its own, and a Payment still `Requested` 12 hours after its creation is voided.
     */
    public ?int $timeout;

    /**
     * When the Payment counts as paid. Defaults to `Standard`. With `Standard` the Payment stays open until its whole amount is paid, so the customer can combine several payment gateways, such as meal vouchers and a card for the rest. With `Partial` the first successful transaction completes the Payment, whatever it paid: `amount` is set to the paid amount, and anything left is settled outside this Payment. A failed transaction leaves the Payment open for another attempt. With `AnyTransaction` the Payment gets a single attempt: it completes as with `Partial` when the first transaction succeeds, and is voided when it fails. Use it on a card reader, where a declined card has to end the Payment. With `Partial` and `AnyTransaction` the checkout does not capture the transactions it authorized, so confirm the Payment or set `isAutoConfirm`. Both require a subscription plan that includes partial payments, except in sandbox.
     * One of the {@see CompletionMode} constants.
     */
    public ?string $completionMode;

    /**
     * The ID of an existing store, in the form `sto_01jkqh8ksaeszr3kme0vcq0hbz`. Use `store` instead to identify it by your own identifiers.
     */
    public ?string $storeId;

    public ?CreatePaymentRequestStore $store;

    /**
     * Transactions to prepare on the Payment, each bound to a specific payment gateway. Use it to charge a gateway server-side instead of letting the customer pick one on the Checkout page.
     *
     * @var null|list<CreatePaymentRequestTransaction>
     */
    public ?array $transactions;

    /**
     * Enable or disable auto confirm.
     * If you don't specify anything the default Merchant configuration will be used.
     * When auto confirm is enabled, payment is automatically confirmed before redirecting customer to RedirectSuccessUrl.
     * When auto confirm is disabled, payment requires manual confirmation.
     */
    public ?bool $isAutoConfirm;

    /**
     * What to do when the amount to charge is below the minimum a payment gateway accepts — 0.50 EUR on most card gateways. The request is raised to that minimum, and this decides what happens to the part that exceeds what is due: with `None` it stays with you, with `Credit` it is credited to the customer's wallet. With `HideGateways` the amount is not raised at all: the gateway is not offered on the Checkout page, and a transaction requested on it fails with `AMOUNT_BELOW_MINIMUM`. Use `HideGateways` when nobody can pick another method, such as a charge on a terminal. If you don't specify anything the default Merchant configuration will be used.
     * One of the {@see ExceedingAmountMode} constants.
     */
    public ?string $exceedingAmountMode;

    /**
     * The URL to which Voucherly should send customers when payment is complete.
     */
    public string $redirectOkUrl;

    /**
     * The URL to which Voucherly should send customers when payment is cancelled or failed.
     */
    public string $redirectKoUrl;

    /**
     * The URL where Voucherly will send a Server-to-Server (S2S) callback notification when the payment status changes. This allows your system to be notified synchronously about payment updates.
     */
    public ?string $callbackUrl;

    /**
     * The language of Voucherly Checkout page.
     */
    public ?string $language;

    /**
     * The country of the purchase. If different from Italy, voucher payment gateways are not shown.
     */
    public ?string $country;

    /**
     * Purchase shipping address.
     */
    public ?string $shippingAddress;

    protected static function types(): array
    {
        return [
            'referenceDate' => 'date',
            'metadata' => 'map',
            'lines' => [PaymentLineRequest::class],
            'discounts' => [PaymentDiscount::class],
            'store' => CreatePaymentRequestStore::class,
            'transactions' => [CreatePaymentRequestTransaction::class],
        ];
    }
}
