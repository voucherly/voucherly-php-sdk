<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\DeclineErrorCode;
use VoucherlyApi\Enum\TransactionErrorCode;
use VoucherlyApi\VoucherlyObject;

/**
 * The error encountered during the Transaction, if any.
 */
class TransactionError extends VoucherlyObject
{
    /**
     * One of the {@see TransactionErrorCode} constants.
     */
    public ?string $code;

    /**
     * One of the {@see DeclineErrorCode} constants.
     */
    public ?string $declineErrorCode;

    public ?ExternalError $externalError;

    protected static function types(): array
    {
        return [
            'externalError' => ExternalError::class,
        ];
    }
}
