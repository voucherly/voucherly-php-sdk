<?php

namespace VoucherlyApi\Enum;

/**
 * When the Payment counts as paid. Defaults to `Standard`. With `Standard` the Payment stays open until its whole amount is paid, so the customer can combine several payment gateways, such as meal vouchers and a card for the rest. With `Partial` the first successful transaction completes the Payment, whatever it paid: `amount` is set to the paid amount, and anything left is settled outside this Payment. A failed transaction leaves the Payment open for another attempt. With `AnyTransaction` the Payment gets a single attempt: it completes as with `Partial` when the first transaction succeeds, and is voided when it fails. Use it on a card reader, where a declined card has to end the Payment. With `Partial` and `AnyTransaction` the checkout does not capture the transactions it authorized, so confirm the Payment or set `isAutoConfirm`. Both require a subscription plan that includes partial payments, except in sandbox.
 */
final class CompletionMode
{
    public const STANDARD = 'Standard';

    public const PARTIAL = 'Partial';

    public const ANY_TRANSACTION = 'AnyTransaction';
}
