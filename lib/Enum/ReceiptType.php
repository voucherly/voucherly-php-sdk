<?php

namespace VoucherlyApi\Enum;

/**
 * The fiscal type of the Receipt:
 * - `Sale`: receipt issued for a successful Payment.
 * - `Void`: receipt issued for a void or refund operation.
 */
final class ReceiptType
{
    public const SALE = 'Sale';

    public const VOID = 'Void';
}
