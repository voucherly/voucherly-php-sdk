<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * A Store is a point of sale of a Merchant, with its own address, POS connection and payment configuration.
 */
class Store extends VoucherlyObject
{
    /**
     * Unique identifier for the Store.
     */
    public ?string $id;

    /**
     * The ID of the Concept Store this Store belongs to.
     */
    public ?string $conceptStoreId;

    /**
     * The name of the Concept Store this Store belongs to. Returned only when `conceptStore` is requested through the `include` parameter. Retrieve the Concept Store from `/v1/concept_stores/{conceptStoreId}` to read its other fields.
     */
    public ?string $conceptStoreName;

    /**
     * The ID of the Store Area this Store belongs to.
     */
    public ?string $storeAreaId;

    /**
     * The name of the Store Area this Store belongs to. Returned only when `storeArea` is requested through the `include` parameter. Retrieve the Store Area from `/v1/store_areas/{storeAreaId}` to read its other fields.
     */
    public ?string $storeAreaName;

    /**
     * The display name of the Store.
     */
    public ?string $name;

    /**
     * A URL-safe identifier for the Store, unique across the Merchant.
     */
    public ?string $slug;

    public ?StorePos $pos;

    /**
     * Your own identifier for the Store, unique across the Merchant.
     */
    public ?string $externalId1;

    /**
     * A second identifier of your own for the Store, unique across the Merchant.
     */
    public ?string $externalId2;

    public ?StoreLocation $address;

    /**
     * Whether the Store is operational.
     */
    public ?bool $isActive;

    /**
     * The ID of the PaymentGatewayConfiguration used by this Store. When null, the Merchant default configuration applies.
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

    /**
     * The current health of the Store POS connection. Returned only when `status` is requested through the `include` parameter, and only for active Stores that have `pos` configured.
     */
    public ?StoreStatus $status;

    /**
     * The UTC timestamp when the Store was created.
     */
    public ?string $createdOnUtc;

    protected static function types(): array
    {
        return [
            'pos' => StorePos::class,
            'address' => StoreLocation::class,
            'status' => StoreStatus::class,
        ];
    }
}
