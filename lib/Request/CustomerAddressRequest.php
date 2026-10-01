<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Request body for creating or updating a Customer address.
 */
class CustomerAddressRequest extends VoucherlyObject
{
    /**
     * A friendly label or name for this address (e.g., "Home", "Work").
     * Required.
     */
    public string $label;

    /**
     * The street name of the address.
     * Required.
     */
    public string $streetName;

    /**
     * The street number of the address.
     * Required.
     */
    public string $streetNumber;

    /**
     * The city of the address.
     * Required.
     */
    public string $city;

    /**
     * The province or state of the address.
     * Required.
     */
    public string $province;

    /**
     * The postal or ZIP code of the address.
     * Required.
     */
    public string $postalCode;

    /**
     * The country code of the address (e.g., "IT" for Italy).
     * Required.
     */
    public string $country;
}
