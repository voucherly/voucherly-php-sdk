<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * An address associated with a Company, typically used for delivery purposes.
 */
class CompanyAddress extends VoucherlyObject
{
    /**
     * Unique identifier for the address.
     */
    public ?string $id;

    /**
     * A friendly label or name for this address (e.g., "Main Office", "Warehouse").
     */
    public ?string $label;

    /**
     * The street name of the address.
     */
    public ?string $streetName;

    /**
     * The street number of the address.
     */
    public ?string $streetNumber;

    /**
     * The city of the address.
     */
    public ?string $city;

    /**
     * The province or state of the address.
     */
    public ?string $province;

    /**
     * The postal or ZIP code of the address.
     */
    public ?string $postalCode;

    /**
     * The country code of the address (e.g., "IT" for Italy).
     */
    public ?string $country;

    /**
     * The delivery fee amount for this address, in cents.
     */
    public ?int $deliveryFeeAmount;

    /**
     * The unique identifier of the store associated with this address.
     */
    public ?string $storeId;
}
