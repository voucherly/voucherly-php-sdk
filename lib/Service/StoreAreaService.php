<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Page;
use VoucherlyApi\Model\StoreArea;
use VoucherlyApi\Request\DeleteStoreAreaParams;
use VoucherlyApi\Request\ListStoreAreaParams;
use VoucherlyApi\Request\StoreAreaRequest;

final class StoreAreaService extends AbstractService
{
    /**
     * List all Store Areas.
     *
     * @return Page<StoreArea>
     */
    public function list(?ListStoreAreaParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', '/v1/store_areas', null, $params), StoreArea::class);
    }

    /**
     * Create a Store Area.
     */
    public function create(StoreAreaRequest $request): StoreArea
    {
        return StoreArea::constructFrom($this->requestJson('POST', '/v1/store_areas', $request));
    }

    /**
     * Retrieve a Store Area.
     */
    public function retrieve(string $id): StoreArea
    {
        return StoreArea::constructFrom($this->requestJson('GET', self::path('/v1/store_areas/%s', $id)));
    }

    /**
     * Update a Store Area.
     */
    public function update(string $id, StoreAreaRequest $request): StoreArea
    {
        return StoreArea::constructFrom($this->requestJson('PUT', self::path('/v1/store_areas/%s', $id), $request));
    }

    /**
     * Delete a Store Area.
     * Permanently deletes the Store Area.
     * The Stores that referenced it are never deleted: pass `migrateToStoreAreaId` to reassign them to another Store Area, or omit it to leave them without one (their `storeAreaId` becomes null).
     * Call `GET /v1/stores?storeAreaId={id}` first to list the Stores this operation will affect.
     */
    public function delete(string $id, ?DeleteStoreAreaParams $params = null): void
    {
        $this->requestNoContent('DELETE', self::path('/v1/store_areas/%s', $id), $params);
    }
}
