<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * The postal address of the Store.
 */
class StoreLocation extends VoucherlyObject
{
    /**
     * The street name.
     */
    public ?string $streetName;

    /**
     * The street number.
     */
    public ?string $streetNumber;

    /**
     * The city.
     */
    public ?string $city;

    /**
     * The two-letter province code.
     */
    public ?string $province;

    /**
     * The postal code.
     */
    public ?string $postalCode;

    /**
     * The country.
     */
    public ?string $country;
}
