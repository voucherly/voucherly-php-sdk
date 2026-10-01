<?php

namespace VoucherlyApi\Enum;

/**
 * Type of wallet movement:
 * - `PAYMENT`: amount debited from the wallet to settle a Payment.
 * - `PAYMENT_R`: reversal of a `PAYMENT` movement.
 * - `WALLET`: amount credited to the wallet (e.g., refund issued as credit).
 * - `WALLET_R`: reversal of a `WALLET` movement.
 * - `ADJUSTMENT`: manual adjustment performed by an operator.
 * - `EXCESS`: amount credited to the wallet because a Transaction collected more than the Payment required.
 * - `EXCESS_R`: reversal of an `EXCESS` movement.
 */
final class CustomerWalletAction
{
    public const PAYMENT = 'PAYMENT';

    public const PAYMENT_R = 'PAYMENT_R';

    public const WALLET = 'WALLET';

    public const WALLET_R = 'WALLET_R';

    public const ADJUSTMENT = 'ADJUSTMENT';

    public const EXCESS = 'EXCESS';

    public const EXCESS_R = 'EXCESS_R';
}
