<?php

namespace VoucherlyApi\Tests\Live;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Enum\PaymentInclude;
use VoucherlyApi\Enum\PaymentStatus;
use VoucherlyApi\Enum\StoreInclude;
use VoucherlyApi\Exception\ConflictException;
use VoucherlyApi\Exception\NotFoundException;
use VoucherlyApi\Request\ConceptStoreRequest;
use VoucherlyApi\Request\CreateCustomerRequest;
use VoucherlyApi\Request\CreatePaymentRequest;
use VoucherlyApi\Request\CustomerAddressRequest;
use VoucherlyApi\Request\ListConceptStoreParams;
use VoucherlyApi\Request\ListStoreAreaParams;
use VoucherlyApi\Request\ListStoreParams;
use VoucherlyApi\Request\RetrievePaymentParams;
use VoucherlyApi\Request\RetrieveStoreParams;
use VoucherlyApi\Request\StoreAreaRequest;
use VoucherlyApi\Request\StoreRequest;
use VoucherlyApi\Request\UpdateCustomerRequest;
use VoucherlyApi\Tests\Support\SpecExamples;
use VoucherlyApi\VoucherlyClient;

/**
 * Calls that create data in the sandbox, run only when VOUCHERLY_API_KEY holds a sandbox key and VOUCHERLY_LIVE_WRITES is 1.
 *
 * @group live
 */
final class SandboxWriteTest extends TestCase
{
    private const STORE_NAME = 'SDK test store';

    private VoucherlyClient $client;

    protected function setUp(): void
    {
        $key = (string) getenv('VOUCHERLY_API_KEY');
        if ('' === $key || '1' !== getenv('VOUCHERLY_LIVE_WRITES')) {
            self::markTestSkipped('VOUCHERLY_API_KEY and VOUCHERLY_LIVE_WRITES=1 are needed.');
        }
        if (0 !== strpos($key, 'sk_sand_')) {
            self::fail('The live tests run only with a sandbox key (sk_sand_).');
        }

        $this->client = new VoucherlyClient(['apiKey' => $key, 'app' => 'voucherly-php-sdk-tests', 'appVersion' => VoucherlyClient::VERSION]);
    }

    public function testCustomerAndItsAddresses(): void
    {
        $create = new CreateCustomerRequest();
        $create->email = 'sdk-php-' . uniqid() . '@example.com';
        $create->firstName = 'Mario';
        $create->lastName = 'Rossi';
        $create->phoneNumber = '+393331234567';
        $create->metadata = ['source' => 'voucherly-php-sdk-tests'];
        $customer = $this->client->customers->create($create);

        self::assertSame($create->email, $this->client->customers->retrieve($customer->id)->email);

        $update = new UpdateCustomerRequest();
        $update->firstName = 'Luigi';
        $update->phoneNumber = null;
        $updated = $this->client->customers->update($customer->id, $update);

        self::assertSame('Luigi', $updated->firstName);
        self::assertSame('Rossi', $updated->lastName);
        self::assertNull($updated->phoneNumber);

        $address = CustomerAddressRequest::fromArray(['label' => 'Home', 'streetName' => 'Via Roma', 'streetNumber' => '1', 'city' => 'Milano', 'province' => 'MI', 'postalCode' => '20121', 'country' => 'IT']);
        $created = $this->client->customers->createAddress($customer->id, $address);
        $address->label = 'Office';
        self::assertSame('Office', $this->client->customers->updateAddress($customer->id, $created->id, $address)->label);
        self::assertSame('Office', $this->client->customers->retrieveAddress($customer->id, $created->id)->label);

        $page = $this->client->customers->listAddresses($customer->id);
        self::assertSame([$created->id], array_map(static fn ($item) => $item->id, $page->items));

        $this->client->customers->deleteAddress($customer->id, $created->id);
        $this->expectException(NotFoundException::class);
        $this->client->customers->retrieveAddress($customer->id, $created->id);
    }

    public function testPaymentCreatedRetrievedAndVoided(): void
    {
        $request = CreatePaymentRequest::fromArray(SpecExamples::requestExamples('create-payment')['New customer']);
        $request->customerEmail = 'sdk-php-' . uniqid() . '@example.com';
        $request->referenceId = 'sdk-php-' . uniqid();

        $payment = $this->client->payments->create($request);

        self::assertSame(PaymentStatus::REQUESTED, $payment->status);
        self::assertNotNull($payment->checkoutUrl);

        $params = new RetrievePaymentParams();
        $params->include = [PaymentInclude::LINES, PaymentInclude::DISCOUNTS, PaymentInclude::TRANSACTIONS];
        $retrieved = $this->client->payments->retrieve($payment->id, $params);
        self::assertSame($request->referenceId, $retrieved->referenceId);
        self::assertCount(1, $retrieved->lines);
        self::assertSame('Muffin', $retrieved->lines[0]->productName);

        self::assertSame(PaymentStatus::VOIDED, $this->client->payments->void($payment->id)->status);

        try {
            $this->client->payments->void($payment->id);
            self::fail('A voided Payment was voided again.');
        } catch (ConflictException $exception) {
            self::assertNotNull($exception->getErrorCode());
        }
    }

    public function testConceptStoreAndStoreArea(): void
    {
        $conceptStore = $this->client->conceptStores->create(ConceptStoreRequest::fromArray(['name' => 'SDK test ' . uniqid(), 'externalId1' => 'SDK-1']));
        $update = ConceptStoreRequest::fromArray(['name' => $conceptStore->name, 'externalId1' => 'SDK-2']);
        self::assertSame('SDK-2', $this->client->conceptStores->update($conceptStore->id, $update)->externalId1);
        $filter = new ListConceptStoreParams();
        $filter->ids = [$conceptStore->id];
        self::assertCount(1, $this->client->conceptStores->list($filter)->items);
        $this->client->conceptStores->delete($conceptStore->id);

        $storeArea = $this->client->storeAreas->create(StoreAreaRequest::fromArray(['name' => 'SDK test ' . uniqid()]));
        self::assertSame($storeArea->name, $this->client->storeAreas->retrieve($storeArea->id)->name);
        $renamed = $this->client->storeAreas->update($storeArea->id, StoreAreaRequest::fromArray(['name' => $storeArea->name . ' renamed']));
        self::assertStringEndsWith(' renamed', $renamed->name);
        $areas = new ListStoreAreaParams();
        $areas->ids = [$storeArea->id];
        self::assertCount(1, $this->client->storeAreas->list($areas)->items);
        $this->client->storeAreas->delete($storeArea->id);

        $this->expectException(NotFoundException::class);
        $this->client->storeAreas->retrieve($storeArea->id);
    }

    public function testStoreCreatedOnceAndUpdated(): void
    {
        $filter = new ListStoreParams();
        $filter->name = self::STORE_NAME;
        $existing = $this->client->stores->list($filter)->items;

        $request = StoreRequest::fromArray(SpecExamples::requestExamples('create-store')['default']);
        $request->name = self::STORE_NAME;
        $request->slug = 'sdk-test-store';
        $request->externalId1 = 'SDK-' . uniqid();

        $store = [] === $existing ? $this->client->stores->create($request) : $this->client->stores->update($existing[0]->id, $request);
        self::assertSame($request->externalId1, $store->externalId1);

        $include = new RetrieveStoreParams();
        $include->include = [StoreInclude::STATUS];
        self::assertSame(self::STORE_NAME, $this->client->stores->retrieve($store->id, $include)->name);
    }
}
