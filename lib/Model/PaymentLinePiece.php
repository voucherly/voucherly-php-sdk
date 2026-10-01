<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * One item delivered on a line sold by measure, with the amount charged for it.
 */
class PaymentLinePiece extends VoucherlyObject
{
    /**
     * The amount charged for this piece, in cents, net of any discount. It is taken as it is, and it may exceed the ordered price.
     */
    public ?int $finalAmount;

    /**
     * The measured content of the piece, in the unit of `unit.code`. Printed on the documents, never used to compute anything.
     */
    public ?float $size;
}
