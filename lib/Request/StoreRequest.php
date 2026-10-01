<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Model\StoreLocation;
use VoucherlyApi\Model\StorePos;
use VoucherlyApi\VoucherlyObject;

/**
 * The writable fields of a Store. Every field is replaced on update; omitted optional fields are cleared.
 */
class StoreRequest extends VoucherlyObject
{
    /**
     * The ID of the Concept Store this Store belongs to. It must belong to the authenticated Merchant.
     */
    public ?string $conceptStoreId;

    /**
     * The ID of the Store Area this Store belongs to. It must belong to the authenticated Merchant.
     */
    public ?string $storeAreaId;

    /**
     * The display name of the Store. Leading and trailing whitespace is removed.
     * Required.
     */
    public string $name;

    /**
     * A URL-safe identifier for the Store, unique across the Merchant. Leading and trailing whitespace is removed; a blank value is stored as null.
     */
    public ?string $slug;

    public ?StorePos $pos;

    /**
     * Your own identifier for the Store, unique across the Merchant. Required when `pos` is set. Leading and trailing whitespace is removed; a blank value is stored as null.
     */
    public ?string $externalId1;

    /**
     * A second identifier of your own for the Store, unique across the Merchant. Leading and trailing whitespace is removed; a blank value is stored as null.
     */
    public ?string $externalId2;

    public ?StoreLocation $address;

    /**
     * Whether the Store is operational. Unlike the other optional fields, omitting it does not clear it but defaults it to true, so an update that leaves it out reactivates the Store.
     */
    public ?bool $isActive;

    /**
     * The ID of the PaymentGatewayConfiguration used by this Store. It must belong to the authenticated Merchant and be active. When null, the Merchant default configuration applies.
     */
    public ?string $paymentGatewayConfigurationId;

    /**
     * Whether the Store accepts e-commerce orders.
     */
    public ?bool $ecommerceIsActive;

    /**
     * Whether the Store accepts e-commerce orders for delivery.
     */
    public ?bool $ecommerceDeliveryIsActive;

    /**
     * Whether the Store accepts e-commerce orders for pickup.
     */
    public ?bool $ecommercePickupIsActive;

    /**
     * Whether the Store accepts e-commerce reservations.
     */
    public ?bool $ecommerceReservationIsActive;

    protected static function types(): array
    {
        return [
            'pos' => StorePos::class,
            'address' => StoreLocation::class,
        ];
    }
}
