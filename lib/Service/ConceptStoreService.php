<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\ConceptStore;
use VoucherlyApi\Model\Page;
use VoucherlyApi\Request\ConceptStoreRequest;
use VoucherlyApi\Request\DeleteConceptStoreParams;
use VoucherlyApi\Request\ListConceptStoreParams;

final class ConceptStoreService extends AbstractService
{
    /**
     * List all Concept Stores.
     *
     * @return Page<ConceptStore>
     */
    public function list(?ListConceptStoreParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', '/v1/concept_stores', null, $params), ConceptStore::class);
    }

    /**
     * Create a Concept Store.
     */
    public function create(ConceptStoreRequest $request): ConceptStore
    {
        return ConceptStore::constructFrom($this->requestJson('POST', '/v1/concept_stores', $request));
    }

    /**
     * Retrieve a Concept Store.
     */
    public function retrieve(string $id): ConceptStore
    {
        return ConceptStore::constructFrom($this->requestJson('GET', self::path('/v1/concept_stores/%s', $id)));
    }

    /**
     * Update a Concept Store.
     * Replaces every writable field of the Concept Store.
     * Fields omitted from the request body are cleared, not preserved.
     */
    public function update(string $id, ConceptStoreRequest $request): ConceptStore
    {
        return ConceptStore::constructFrom($this->requestJson('PUT', self::path('/v1/concept_stores/%s', $id), $request));
    }

    /**
     * Delete a Concept Store.
     * Permanently deletes the Concept Store.
     * The Stores that referenced it are never deleted: pass `migrateToConceptStoreId` to reassign them to another Concept Store, or omit it to leave them without one (their `conceptStoreId` becomes null).
     * Call `GET /v1/stores?conceptStoreId={id}` first to list the Stores this operation will affect.
     */
    public function delete(string $id, ?DeleteConceptStoreParams $params = null): void
    {
        $this->requestNoContent('DELETE', self::path('/v1/concept_stores/%s', $id), $params);
    }
}
