<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\PackagingType;
use VoucherlyApi\Enum\TenantMode;
use VoucherlyApi\VoucherlyObject;

/**
 * The Company object.
 */
class Company extends VoucherlyObject
{
    public ?string $id;

    /**
     * The ID of the merchant this Company belongs to, that is your own merchant.
     */
    public ?string $merchantId;

    /**
     * One of the {@see TenantMode} constants.
     */
    public ?string $tenant;

    /**
     * A unique 6-character code that can be used to join or reference this Company.
     */
    public ?string $joinCode;

    /**
     * Company name.
     */
    public ?string $name;

    /**
     * The percentage applied to catalogue prices for this Company. Positive values raise them, negative values lower them.
     */
    public ?int $priceAdjustmentPercentage;

    /**
     * The prepaid policy applied to the Customers of this Company.
     */
    public ?PrepaidPolicy $prepaidPolicy;

    /**
     * The packaging used by the Customers of this Company that do not set one of their own. Null when the Company follows the ecommerce site default.
     * One of the {@see PackagingType} constants.
     */
    public ?string $packagingType;

    /**
     * An array of delivery addresses associated with this Company. Each address can have specific delivery fees and store information.
     *
     * @var null|list<CompanyAddress>
     */
    public ?array $addresses;

    protected static function types(): array
    {
        return [
            'prepaidPolicy' => PrepaidPolicy::class,
            'addresses' => [CompanyAddress::class],
        ];
    }
}
