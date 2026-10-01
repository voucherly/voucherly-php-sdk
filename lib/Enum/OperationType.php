<?php

namespace VoucherlyApi\Enum;

/**
 * The kind of operation performed on a Transaction.
 */
final class OperationType
{
    public const CONFIRM = 'Confirm';

    public const REFUND = 'Refund';

    public const CANCEL = 'Cancel';

    public const REVERSE = 'Reverse';
}
