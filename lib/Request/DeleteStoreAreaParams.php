<?php

namespace VoucherlyApi\Request;

final class DeleteStoreAreaParams extends RequestParams
{
    /**
     * The Store Area the referencing Stores are reassigned to. It must belong to the authenticated Merchant and must be different from `id`. When omitted, the referencing Stores are left without a Store Area.
     */
    public ?string $migrateToStoreAreaId;
}
