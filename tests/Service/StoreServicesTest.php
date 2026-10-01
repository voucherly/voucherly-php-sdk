<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Enum\StoreInclude;
use VoucherlyApi\Model\ConceptStore;
use VoucherlyApi\Model\Store;
use VoucherlyApi\Model\StoreArea;
use VoucherlyApi\Request\ConceptStoreRequest;
use VoucherlyApi\Request\DeleteConceptStoreParams;
use VoucherlyApi\Request\DeleteStoreAreaParams;
use VoucherlyApi\Request\ListConceptStoreParams;
use VoucherlyApi\Request\ListStoreAreaParams;
use VoucherlyApi\Request\ListStoreParams;
use VoucherlyApi\Request\RetrieveStoreParams;
use VoucherlyApi\Request\StoreAreaRequest;
use VoucherlyApi\Request\StoreRequest;
use VoucherlyApi\Tests\Support\ServiceTestCase;
use VoucherlyApi\Tests\Support\SpecExamples;

final class StoreServicesTest extends ServiceTestCase
{
    public function testListStores(): void
    {
        $json = $this->respondWithSample('list-store');
        $params = new ListStoreParams();
        $params->name = 'Milano';
        $params->isActive = true;
        $params->conceptStoreId = 'conce_1';
        $params->storeAreaId = 'starea_1';
        $params->include = [StoreInclude::CONCEPT_STORE, StoreInclude::STATUS];

        $page = $this->client->stores->list($params);

        $this->assertOperation('list-store', [], 'name=Milano&isActive=true&conceptStoreId=conce_1&storeAreaId=starea_1&include=conceptStore&include=status');
        self::assertInstanceOf(Store::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testCreateStore(): void
    {
        $json = $this->respondWithSample('create-store');
        $example = SpecExamples::requestExamples('create-store')['default'];

        $store = $this->client->stores->create(StoreRequest::fromArray($example));

        $this->assertOperation('create-store', [], '', $example);
        self::assertReadsEveryMember($json, $store);
    }

    public function testRetrieveStore(): void
    {
        $json = $this->respondWithSample('retrieve-store');
        $params = new RetrieveStoreParams();
        $params->include = [StoreInclude::STORE_AREA];

        $store = $this->client->stores->retrieve('sto_1', $params);

        $this->assertOperation('retrieve-store', ['id' => 'sto_1'], 'include=storeArea');
        self::assertReadsEveryMember($json, $store);
    }

    public function testUpdateStore(): void
    {
        $json = $this->respondWithSample('update-store');
        $example = SpecExamples::requestExamples('update-store')['default'];

        $store = $this->client->stores->update('sto_1', StoreRequest::fromArray($example));

        $this->assertOperation('update-store', ['id' => 'sto_1'], '', $example);
        self::assertReadsEveryMember($json, $store);
    }

    public function testListConceptStores(): void
    {
        $json = $this->respondWithSample('list-concept-store');
        $params = new ListConceptStoreParams();
        $params->name = 'Bistrot';
        $params->ids = ['conce_1', 'conce_2'];

        $page = $this->client->conceptStores->list($params);

        $this->assertOperation('list-concept-store', [], 'name=Bistrot&ids=conce_1&ids=conce_2');
        self::assertInstanceOf(ConceptStore::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testCreateConceptStore(): void
    {
        $json = $this->respondWithSample('create-concept-store');
        $example = SpecExamples::requestExamples('create-concept-store')['default'];

        $conceptStore = $this->client->conceptStores->create(ConceptStoreRequest::fromArray($example));

        $this->assertOperation('create-concept-store', [], '', $example);
        self::assertReadsEveryMember($json, $conceptStore);
    }

    public function testRetrieveConceptStore(): void
    {
        $json = $this->respondWithSample('retrieve-concept-store');

        $conceptStore = $this->client->conceptStores->retrieve('conce_1');

        $this->assertOperation('retrieve-concept-store', ['id' => 'conce_1']);
        self::assertReadsEveryMember($json, $conceptStore);
    }

    public function testUpdateConceptStore(): void
    {
        $json = $this->respondWithSample('update-concept-store');
        $example = SpecExamples::requestExamples('update-concept-store')['default'];

        $conceptStore = $this->client->conceptStores->update('conce_1', ConceptStoreRequest::fromArray($example));

        $this->assertOperation('update-concept-store', ['id' => 'conce_1'], '', $example);
        self::assertReadsEveryMember($json, $conceptStore);
    }

    public function testDeleteConceptStoreMigratingItsStores(): void
    {
        $this->respondWithSample('delete-concept-store');
        $params = new DeleteConceptStoreParams();
        $params->migrateToConceptStoreId = 'conce_2';

        $this->client->conceptStores->delete('conce_1', $params);

        $this->assertOperation('delete-concept-store', ['id' => 'conce_1'], 'migrateToConceptStoreId=conce_2');
    }

    public function testDeleteConceptStore(): void
    {
        $this->respondWithSample('delete-concept-store');

        $this->client->conceptStores->delete('conce_1');

        $this->assertOperation('delete-concept-store', ['id' => 'conce_1']);
    }

    public function testListStoreAreas(): void
    {
        $json = $this->respondWithSample('list-store-area');
        $params = new ListStoreAreaParams();
        $params->ids = ['starea_1'];
        $params->start = 'starea_next';

        $page = $this->client->storeAreas->list($params);

        $this->assertOperation('list-store-area', [], 'ids=starea_1&start=starea_next');
        self::assertInstanceOf(StoreArea::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testCreateStoreArea(): void
    {
        $json = $this->respondWithSample('create-store-area');
        $example = SpecExamples::requestExamples('create-store-area')['default'];

        $storeArea = $this->client->storeAreas->create(StoreAreaRequest::fromArray($example));

        $this->assertOperation('create-store-area', [], '', $example);
        self::assertReadsEveryMember($json, $storeArea);
    }

    public function testRetrieveStoreArea(): void
    {
        $json = $this->respondWithSample('retrieve-store-area');

        $storeArea = $this->client->storeAreas->retrieve('starea_1');

        $this->assertOperation('retrieve-store-area', ['id' => 'starea_1']);
        self::assertReadsEveryMember($json, $storeArea);
    }

    public function testUpdateStoreArea(): void
    {
        $json = $this->respondWithSample('update-store-area');
        $example = SpecExamples::requestExamples('update-store-area')['default'];

        $storeArea = $this->client->storeAreas->update('starea_1', StoreAreaRequest::fromArray($example));

        $this->assertOperation('update-store-area', ['id' => 'starea_1'], '', $example);
        self::assertReadsEveryMember($json, $storeArea);
    }

    public function testDeleteStoreAreaMigratingItsStores(): void
    {
        $this->respondWithSample('delete-store-area');
        $params = new DeleteStoreAreaParams();
        $params->migrateToStoreAreaId = 'starea_2';

        $this->client->storeAreas->delete('starea_1', $params);

        $this->assertOperation('delete-store-area', ['id' => 'starea_1'], 'migrateToStoreAreaId=starea_2');
    }
}
