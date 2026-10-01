<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * The store the Payment belongs to, identified by your own identifiers. When no store matches, Voucherly creates one on the fly.
 */
class CreatePaymentRequestStore extends VoucherlyObject
{
    /**
     * Your primary identifier for the store.
     */
    public ?string $externalId1;

    /**
     * Your secondary identifier for the store.
     */
    public ?string $externalId2;

    /**
     * The name of the store, used when Voucherly has to create it.
     */
    public ?string $name;
}
