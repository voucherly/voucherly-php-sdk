<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Page;
use VoucherlyApi\Model\Store;
use VoucherlyApi\Request\ListStoreParams;
use VoucherlyApi\Request\RetrieveStoreParams;
use VoucherlyApi\Request\StoreRequest;

final class StoreService extends AbstractService
{
    /**
     * List all Stores.
     *
     * @return Page<Store>
     */
    public function list(?ListStoreParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', '/v1/stores', null, $params), Store::class);
    }

    /**
     * Create a Store.
     * Creates a Store for the authenticated Merchant.
     * `conceptStoreName`, `storeAreaName` and the `status` object are never returned by this operation; retrieve the Store with the `include` parameter to read them.
     */
    public function create(StoreRequest $request): Store
    {
        return Store::constructFrom($this->requestJson('POST', '/v1/stores', $request));
    }

    /**
     * Retrieve a Store.
     */
    public function retrieve(string $id, ?RetrieveStoreParams $params = null): Store
    {
        return Store::constructFrom($this->requestJson('GET', self::path('/v1/stores/%s', $id), null, $params));
    }

    /**
     * Update a Store.
     * Replaces every writable field of the Store.
     * Fields omitted from the request body are cleared, not preserved.
     * `conceptStoreName`, `storeAreaName` and the `status` object are never returned by this operation; retrieve the Store with the `include` parameter to read them.
     */
    public function update(string $id, StoreRequest $request): Store
    {
        return Store::constructFrom($this->requestJson('PUT', self::path('/v1/stores/%s', $id), $request));
    }
}
