<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * The action the payer still has to perform for the transaction to complete. Its shape depends on the PaymentGateway.
 */
class TransactionNextAction extends VoucherlyObject
{
    /**
     * The gateway specific parameters describing the action.
     *
     * @var null|array<string, mixed>
     */
    public ?array $params;
}
