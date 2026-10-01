<?php

namespace VoucherlyApi\Enum;

/**
 * How money already captured is given back when the confirmed amounts are lower than what was captured. Authorizations are never refunded: they are captured partially or voided.
 * - `NoRefund` (default): nothing is given back, and the request fails with `REFUND_REQUIRED` without touching any transaction.
 * - `Gateway`: refunded through the payment gateway. When its configuration forbids a partial refund, the request fails before touching any transaction; when the gateway itself refuses the refund it has already been asked for, the captures of this confirmation are done and `operations` lists them.
 * - `Credit`: credited to the customer's wallet, without calling the gateway. A credit coming from meal vouchers can be spent only on food.
 * - `GatewayOrCredit`: refunded through the gateway where it allows it, credited to the wallet otherwise.
 */
final class RefundMode
{
    public const NO_REFUND = 'NoRefund';

    public const GATEWAY = 'Gateway';

    public const CREDIT = 'Credit';

    public const GATEWAY_OR_CREDIT = 'GatewayOrCredit';
}
