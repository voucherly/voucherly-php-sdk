<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * If this is a card PaymentMethod, this contains the user’s card details.
 */
class CreditCardInfo extends VoucherlyObject
{
    /**
     * Card brand.
     */
    public ?string $brand;

    /**
     * Card masked PAN.
     */
    public ?string $pan;

    /**
     * The card's expiration date, typically in MM/YY format.
     */
    public ?string $expiration;

    /**
     * Two-digit number representing the card’s expiration month.
     */
    public ?int $expirationMonth;

    /**
     * Four-digit number representing the card’s expiration year.
     */
    public ?int $expirationYear;

    /**
     * The card product type (e.g., "Visa Classic", "Mastercard Gold").
     */
    public ?string $product;
}
