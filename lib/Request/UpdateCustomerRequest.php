<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Request body for updating a Customer. Any field not provided will be left unchanged.
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
     * The ID of the customer in your own system, such as the user ID of your website. It is unique per environment, so two Customers of the same environment can't share it. Use it to link a Customer that already exists to your user, or pass `null` to unlink it. When another Customer already has it, the request fails with `409 Conflict`.
     */
    public ?string $referenceId;

    /**
     * The language to write to the Customer in, as an ISO 639-1 two-letter code such as `it` or `en`.
     */
    public ?string $language;

    /**
     * The Customer's date of birth, as `yyyy-MM-dd`. It can't be in the future or before 1900-01-01. Pass `null` to clear it.
     */
    public ?\DateTimeInterface $birthDate;

    /**
     * The ID of the Company this Customer is associated with. Pass `null` to detach the Customer from its Company.
     */
    public ?string $companyId;

    /**
     * @var null|array<string, string>
     */
    public ?array $metadata;

    /**
     * Choices the Customer made after the last update, appended to the consent register. They never replace it, and a choice equal to the current one, with the same version, is ignored, so you can send the whole list at every update.
     *
     * @var null|list<ConsentRequest>
     */
    public ?array $consents;

    protected static function types(): array
    {
        return [
            'birthDate' => 'date',
            'metadata' => 'map',
            'consents' => [ConsentRequest::class],
        ];
    }
}
