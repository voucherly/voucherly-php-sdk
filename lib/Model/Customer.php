<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\TenantMode;
use VoucherlyApi\VoucherlyObject;

/**
 * The Customer object.
 */
class Customer extends VoucherlyObject
{
    public ?string $id;

    /**
     * The ID of the merchant this Customer belongs to, that is your own merchant.
     */
    public ?string $merchantId;

    /**
     * One of the {@see TenantMode} constants.
     */
    public ?string $tenant;

    /**
     * The date and time the Customer was created, in UTC.
     */
    public ?string $created;

    /**
     * The customer’s email address.
     */
    public ?string $email;

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
     * The ID of the customer in your own system, such as the user ID of your website. It is unique per environment, so two Customers of the same environment can't share it.
     */
    public ?string $referenceId;

    /**
     * The language the Customer is written to in, as an ISO 639-1 two-letter code.
     */
    public ?string $language;

    /**
     * The ID of the Company this Customer is associated with.
     */
    public ?string $companyId;

    /**
     * The Company this Customer is associated with, returned in full when one exists.
     */
    public ?Company $company;

    public ?Packaging $packaging;

    public ?CustomerWalletTotals $wallet;

    public ?SpendData $spendData;

    /**
     * The current choice of the Customer for each purpose and channel it expressed one for. A consent never expressed is absent.
     *
     * @var null|list<Consent>
     */
    public ?array $consents;

    /**
     * @var null|array<string, string>
     */
    public ?array $metadata;

    protected static function types(): array
    {
        return [
            'company' => Company::class,
            'packaging' => Packaging::class,
            'wallet' => CustomerWalletTotals::class,
            'spendData' => SpendData::class,
            'consents' => [Consent::class],
            'metadata' => 'map',
        ];
    }
}
