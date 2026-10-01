<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * The writable fields of a Concept Store. Every field is replaced on update; omitted optional fields are cleared.
 */
class ConceptStoreRequest extends VoucherlyObject
{
    /**
     * The display name of the Concept Store, unique across the Merchant. Leading and trailing whitespace is removed.
     * Required.
     */
    public string $name;

    /**
     * Your own identifier for the Concept Store, unique across the Merchant. Leading and trailing whitespace is removed; a blank value is stored as null.
     */
    public ?string $externalId1;

    /**
     * A second identifier of your own for the Concept Store, unique across the Merchant. Leading and trailing whitespace is removed; a blank value is stored as null.
     */
    public ?string $externalId2;
}
