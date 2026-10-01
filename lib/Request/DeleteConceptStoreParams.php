<?php

namespace VoucherlyApi\Request;

final class DeleteConceptStoreParams extends RequestParams
{
    /**
     * The Concept Store the referencing Stores are reassigned to. It must belong to the authenticated Merchant and must be different from `id`. When omitted, the referencing Stores are left without a Concept Store.
     */
    public ?string $migrateToConceptStoreId;
}
