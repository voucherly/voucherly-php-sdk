<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Request body for creating a Customer.
 */
class CreateCustomerRequest extends VoucherlyObject
{
    /**
     * The customer’s email address.
     */
    public string $email;

    /**
     * The customer’s first name.
     */
    public ?string $firstName;

    /**
     * The customer’s last name.
     */
    public ?string $lastName;

    /**
     * The customer’s phone number.
     */
    public ?string $phoneNumber;

    /**
     * The ID of the Company this Customer is associated with.
     */
    public ?string $companyId;

    /**
     * @var null|array<string, string>
     */
    public ?array $metadata;

    /**
     * Optional settings for customer creation.
     */
    public ?CreateCustomerRequestOptions $options;

    protected static function types(): array
    {
        return [
            'metadata' => 'map',
            'options' => CreateCustomerRequestOptions::class,
        ];
    }
}
