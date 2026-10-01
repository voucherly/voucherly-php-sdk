<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Request body for updating a Customer. Any field not provided will be left unchanged. `referenceId` cannot be changed via this endpoint.
 */
class UpdateCustomerRequest extends VoucherlyObject
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
     * The ID of the Company this Customer is associated with. Pass `null` to detach the Customer from its Company.
     */
    public ?string $companyId;

    /**
     * @var null|array<string, string>
     */
    public ?array $metadata;

    protected static function types(): array
    {
        return [
            'metadata' => 'map',
        ];
    }
}
