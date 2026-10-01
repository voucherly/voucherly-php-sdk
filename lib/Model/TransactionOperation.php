<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\OperationType;
use VoucherlyApi\VoucherlyObject;

/**
 * A single confirm, refund, cancel or reverse performed on a Transaction.
 */
class TransactionOperation extends VoucherlyObject
{
    /**
     * The identifier of the operation.
     */
    public ?int $id;

    /**
     * One of the {@see OperationType} constants.
     */
    public ?string $operation;

    /**
     * The amount the operation moved, in cents.
     */
    public ?int $amount;

    /**
     * The date and time the operation was performed, in UTC.
     */
    public ?string $occurredOnUtc;

    /**
     * Whether the operation succeeded.
     */
    public ?bool $success;

    /**
     * The identifier of the operation in the PaymentGateway provider system.
     */
    public ?string $externalId;

    /**
     * The error returned when the operation failed.
     */
    public ?TransactionOperationError $error;

    protected static function types(): array
    {
        return [
            'error' => TransactionOperationError::class,
        ];
    }
}
