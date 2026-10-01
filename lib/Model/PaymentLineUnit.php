<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * How a line is sold when the piece is not what it is priced by: a pack of plums is one piece of about 0.75 kg at 1.98 €/kg. Descriptive only: no amount is computed from it, `unitAmount` times `quantity` stays what the customer pays, and your till keeps rounding each piece on its own.
 */
class PaymentLineUnit extends VoucherlyObject
{
    /**
     * The unit of measure, such as `kg` or `l`. At most 10 characters.
     */
    public ?string $code;

    /**
     * The nominal content of one piece, in the unit of `code`. At most three decimals.
     */
    public ?float $size;

    /**
     * The price per unit of measure, in cents.
     */
    public ?int $amount;
}
