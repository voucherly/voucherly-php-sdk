<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\ReceiptType;
use VoucherlyApi\VoucherlyObject;

/**
 * A fiscal Receipt issued for a Payment.
 */
class Receipt extends VoucherlyObject
{
    /**
     * Unique identifier for the Receipt.
     */
    public ?string $id;

    /**
     * The identifier assigned to the Receipt by the upstream fiscal provider.
     */
    public ?string $externalId;

    /**
     * The fiscal Receipt number.
     */
    public ?string $number;

    /**
     * The UTC timestamp when the Receipt was issued.
     */
    public ?string $date;

    /**
     * One of the {@see ReceiptType} constants.
     */
    public ?string $type;

    /**
     * The total amount of the Receipt, in cents.
     */
    public ?int $amount;

    /**
     * The ID of the parent Receipt, when this Receipt is a void/refund of a previously issued Sale Receipt.
     */
    public ?string $parentReceiptId;
}
