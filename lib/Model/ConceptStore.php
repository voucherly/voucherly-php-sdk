<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * A Concept Store is a brand used to group Stores of the same Merchant.
 */
class ConceptStore extends VoucherlyObject
{
    /**
     * Unique identifier for the Concept Store.
     */
    public ?string $id;

    /**
     * The display name of the Concept Store, unique across the Merchant.
     */
    public ?string $name;

    /**
     * Your own identifier for the Concept Store, unique across the Merchant.
     */
    public ?string $externalId1;

    /**
     * A second identifier of your own for the Concept Store, unique across the Merchant.
     */
    public ?string $externalId2;

    /**
     * The UTC timestamp when the Concept Store was created.
     */
    public ?string $createdOnUtc;
}
