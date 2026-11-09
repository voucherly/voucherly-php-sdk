<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * An address associated with a Customer.
 */
class CustomerAddress extends VoucherlyObject
{
    /**
     * Unique identifier for the address.
     */
    public ?string $id;

    /**
     * A friendly label or name for this address (e.g., "Home", "Work").
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
}
