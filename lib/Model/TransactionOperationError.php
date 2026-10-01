<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * The error returned when an operation on a Transaction failed.
 */
class TransactionOperationError extends VoucherlyObject
{
    /**
     * The error as reported by the PaymentGateway provider.
     */
    public ?ExternalError $externalError;

    /**
     * Whether the failure comes from Voucherly rather than from the PaymentGateway provider.
     */
    public ?bool $isSystemError;

    protected static function types(): array
    {
        return [
            'externalError' => ExternalError::class,
        ];
    }
}
