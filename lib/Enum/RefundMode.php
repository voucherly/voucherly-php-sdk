<?php

namespace VoucherlyApi\Enum;

/**
 * How money already captured is given back, on a confirmation at lower amounts and on a refund. Authorizations are never refunded: they are captured partially or voided.
 * - `NoRefund`: nothing is given back. The default of a confirmation, which then fails with `REFUND_REQUIRED` without touching any transaction; not accepted by a refund.
 * - `Gateway`: refunded through the payment gateway, the default of a refund. When its configuration forbids a partial refund, the request fails before touching any transaction; when the gateway itself refuses the refund it has already been asked for, the operations already done stay done and `operations` lists them.
 * - `Credit`: credited to the customer's wallet, without calling the gateway. A credit coming from meal vouchers can be spent only on food. Not accepted on a wallet top-up.
 * - `GatewayOrCredit`: refunded through the gateway where it allows it, credited to the wallet otherwise, also when the gateway refuses the refund. Not accepted on a wallet top-up.
 */
final class RefundMode
{
    public const NO_REFUND = 'NoRefund';

    public const GATEWAY = 'Gateway';

    public const CREDIT = 'Credit';

    public const GATEWAY_OR_CREDIT = 'GatewayOrCredit';
}
