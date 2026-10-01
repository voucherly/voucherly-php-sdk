<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Enum\CustomerInclude;
use VoucherlyApi\Model\CustomerAddress;
use VoucherlyApi\Model\CustomerWalletMovement;
use VoucherlyApi\Request\CreateCustomerRequest;
use VoucherlyApi\Request\CustomerAddressRequest;
use VoucherlyApi\Request\ListCustomerAddressParams;
use VoucherlyApi\Request\ListCustomerParams;
use VoucherlyApi\Request\ListCustomerWalletMovementParams;
use VoucherlyApi\Request\RetrieveCustomerParams;
use VoucherlyApi\Request\RetrieveCustomerPrepaidBalanceParams;
use VoucherlyApi\Request\UpdateCustomerRequest;
use VoucherlyApi\Tests\Support\ServiceTestCase;
use VoucherlyApi\Tests\Support\SpecExamples;

final class CustomerServiceTest extends ServiceTestCase
{
    /**
     * @dataProvider provideCreateCases
     *
     * @param array<string, mixed> $example
     */
    public function testCreate(array $example): void
    {
        $json = $this->respondWithSample('create-customer');

        $customer = $this->client->customers->create(CreateCustomerRequest::fromArray($example));

        $this->assertOperation('create-customer', [], '', $example);
        self::assertReadsEveryMember($json, $customer);
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function provideCreateCases(): iterable
    {
        foreach (SpecExamples::requestExamples('create-customer') as $name => $example) {
            yield $name => [$example];
        }
    }

    public function testList(): void
    {
        $json = $this->respondWithSample('list-customer');
        $params = new ListCustomerParams();
        $params->email = 'mario.rossi@example.com';
        $params->length = 1;

        $page = $this->client->customers->list($params);

        $this->assertOperation('list-customer', [], 'email=mario.rossi%40example.com&length=1');
        self::assertReadsEveryMember($json, $page);
    }

    public function testRetrieve(): void
    {
        $json = $this->respondWithSample('retrieve-customer');
        $params = new RetrieveCustomerParams();
        $params->include = [CustomerInclude::WALLET];

        $customer = $this->client->customers->retrieve('cs_YZOJp96qKlW', $params);

        $this->assertOperation('retrieve-customer', ['id' => 'cs_YZOJp96qKlW'], 'include=Wallet');
        self::assertReadsEveryMember($json, $customer);
    }

    public function testUpdateSendsOnlyTheAssignedFieldsAndAnExplicitNull(): void
    {
        $json = $this->respondWithSample('update-customer');
        $request = new UpdateCustomerRequest();
        $request->email = 'mario.rossi@example.com';
        $request->phoneNumber = null;
        $request->metadata = ['crmId' => '42'];

        $customer = $this->client->customers->update('cs_YZOJp96qKlW', $request);

        $this->assertOperation('update-customer', ['id' => 'cs_YZOJp96qKlW'], '', ['email' => 'mario.rossi@example.com', 'phoneNumber' => null, 'metadata' => ['crmId' => '42']]);
        self::assertReadsEveryMember($json, $customer);
    }

    public function testRetrievePrepaidBalance(): void
    {
        $json = $this->respondWithSample('retrieve-customer-prepaid-balance');

        $balance = $this->client->customers->retrievePrepaidBalance('cs_YZOJp96qKlW', new RetrieveCustomerPrepaidBalanceParams(new \DateTimeImmutable('2026-09-30 23:30:00')));

        $this->assertOperation('retrieve-customer-prepaid-balance', ['customerId' => 'cs_YZOJp96qKlW'], 'date=2026-09-30');
        self::assertReadsEveryMember($json, $balance);
    }

    public function testListWalletMovements(): void
    {
        $json = $this->respondWithSample('list-customer-wallet-movement');
        $params = new ListCustomerWalletMovementParams();
        $params->fromDate = new \DateTimeImmutable('2026-09-01T00:00:00Z');
        $params->toDate = new \DateTimeImmutable('2026-09-30T23:59:59Z');

        $page = $this->client->customers->listWalletMovements('cs_YZOJp96qKlW', $params);

        $this->assertOperation('list-customer-wallet-movement', ['id' => 'cs_YZOJp96qKlW'], 'fromDate=2026-09-01T00%3A00%3A00%2B00%3A00&toDate=2026-09-30T23%3A59%3A59%2B00%3A00');
        self::assertInstanceOf(CustomerWalletMovement::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testListAddresses(): void
    {
        $json = $this->respondWithSample('list-customer-address');

        $params = new ListCustomerAddressParams();
        $params->length = 10;
        $params->start = 'addr_next';

        $page = $this->client->customers->listAddresses('cs_YZOJp96qKlW', $params);

        $this->assertOperation('list-customer-address', ['customerId' => 'cs_YZOJp96qKlW'], 'length=10&start=addr_next');
        self::assertInstanceOf(CustomerAddress::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testCreateAddress(): void
    {
        $json = $this->respondWithSample('create-customer-address');

        $address = $this->client->customers->createAddress('cs_YZOJp96qKlW', $this->addressRequest());

        $this->assertOperation('create-customer-address', ['customerId' => 'cs_YZOJp96qKlW'], '', $this->addressJson());
        self::assertReadsEveryMember($json, $address);
    }

    public function testRetrieveAddress(): void
    {
        $json = $this->respondWithSample('retrieve-customer-address');

        $address = $this->client->customers->retrieveAddress('cs_YZOJp96qKlW', 'addr_1');

        $this->assertOperation('retrieve-customer-address', ['customerId' => 'cs_YZOJp96qKlW', 'addressId' => 'addr_1']);
        self::assertReadsEveryMember($json, $address);
    }

    public function testUpdateAddress(): void
    {
        $json = $this->respondWithSample('update-customer-address');

        $address = $this->client->customers->updateAddress('cs_YZOJp96qKlW', 'addr_1', $this->addressRequest());

        $this->assertOperation('update-customer-address', ['customerId' => 'cs_YZOJp96qKlW', 'addressId' => 'addr_1'], '', $this->addressJson());
        self::assertReadsEveryMember($json, $address);
    }

    public function testDeleteAddress(): void
    {
        $this->respondWithSample('delete-customer-address');

        $this->client->customers->deleteAddress('cs_YZOJp96qKlW', 'addr_1');

        $this->assertOperation('delete-customer-address', ['customerId' => 'cs_YZOJp96qKlW', 'addressId' => 'addr_1']);
    }

    private function addressRequest(): CustomerAddressRequest
    {
        return CustomerAddressRequest::fromArray($this->addressJson());
    }

    /**
     * @return array<string, string>
     */
    private function addressJson(): array
    {
        return ['label' => 'Home', 'streetName' => 'Via Roma', 'streetNumber' => '1', 'city' => 'Milano', 'province' => 'MI', 'postalCode' => '20121', 'country' => 'IT'];
    }
}
