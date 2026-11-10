<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\CustomerOriginRequest;
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
     * The ID of the customer in your own system, such as the user ID of your website. It is unique per environment, so two Customers of the same environment can't share it. When another Customer already has it, the request fails with `409 Conflict`.
     */
    public ?string $referenceId;

    /**
     * The language to write to the Customer in, as an ISO 639-1 two-letter code such as `it` or `en`.
     */
    public ?string $language;

    /**
     * The Customer's date of birth, as `yyyy-MM-dd`. It can't be in the future or before 1900-01-01.
     */
    public ?\DateTimeInterface $birthDate;

    /**
     * The ID of the Company this Customer is associated with.
     */
    public ?string $companyId;

    /**
     * @var null|array<string, string>
     */
    public ?array $metadata;

    /**
     * The choices the Customer made about marketing and profiling, as you collected them. Each one is kept in the consent register with its date, text and version.
     *
     * @var null|list<ConsentRequest>
     */
    public ?array $consents;

    /**
     * When the Customer registered on your website, if it was not now. Send it when you synchronize existing customers. A registration older than 24 hours starts no Marketing automation. It can't be in the future. Defaults to now.
     */
    public ?string $registeredAt;

    /**
     * Where you won the Customer. Voucherly records the other points of acquisition, such as the table or a Payment link, itself, and refuses them here with code `ORIGIN_NOT_DECLARABLE`. Defaults to `Api`.
     * One of the {@see CustomerOriginRequest} constants.
     */
    public ?string $origin;

    /**
     * Optional settings for customer creation.
     */
    public ?CreateCustomerRequestOptions $options;

    protected static function types(): array
    {
        return [
            'birthDate' => 'date',
            'metadata' => 'map',
            'consents' => [ConsentRequest::class],
            'options' => CreateCustomerRequestOptions::class,
        ];
    }
}
