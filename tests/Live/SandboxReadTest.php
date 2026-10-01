<?php

namespace VoucherlyApi\Tests\Live;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Exception\ApiException;
use VoucherlyApi\Exception\BadRequestException;
use VoucherlyApi\Model\Customer;
use VoucherlyApi\Request\ListCustomerParams;
use VoucherlyApi\Request\ListStoreParams;
use VoucherlyApi\Request\RetrieveCustomerParams;
use VoucherlyApi\Request\RetrieveCustomerPrepaidBalanceParams;
use VoucherlyApi\Request\VolumesReportParams;
use VoucherlyApi\VoucherlyClient;
use VoucherlyApi\VoucherlyObject;

/**
 * Read-only calls against the sandbox, run only when VOUCHERLY_API_KEY holds a sandbox key.
 *
 * @group live
 */
final class SandboxReadTest extends TestCase
{
    private VoucherlyClient $client;

    /** @var list<string> */
    private static array $unknownMembers = [];

    protected function setUp(): void
    {
        $key = (string) getenv('VOUCHERLY_API_KEY');
        if ('' === $key) {
            self::markTestSkipped('VOUCHERLY_API_KEY is not set.');
        }
        if (0 !== strpos($key, 'sk_sand_')) {
            self::fail('The live tests run only with a sandbox key (sk_sand_).');
        }

        $this->client = new VoucherlyClient(['apiKey' => $key, 'app' => 'voucherly-php-sdk-tests', 'appVersion' => VoucherlyClient::VERSION]);
    }

    public static function tearDownAfterClass(): void
    {
        if ([] !== self::$unknownMembers) {
            fwrite(STDERR, PHP_EOL . 'Members the spec does not document: ' . implode(', ', array_unique(self::$unknownMembers)) . PHP_EOL);
        }
    }

    public function testListsPaymentGateways(): void
    {
        $list = $this->client->paymentGateways->list();

        self::assertNotEmpty($list->items);
        self::collectUnknownMembers($list);
    }

    public function testListsAndRetrievesCompanies(): void
    {
        $page = $this->client->companies->list();

        self::assertNotNull($page->pagination);
        self::collectUnknownMembers($page);
        if ([] !== $page->items) {
            self::collectUnknownMembers($this->client->companies->retrieve($page->items[0]->id));
        }
    }

    public function testReadsTheCustomersAndWhatHangsFromThem(): void
    {
        $params = new ListCustomerParams();
        $params->length = 5;
        $page = $this->client->customers->list($params);
        self::collectUnknownMembers($page);

        if (true === $page->pagination->hasMore) {
            $next = new ListCustomerParams();
            $next->length = 5;
            $next->start = $page->pagination->nextStart;
            self::collectUnknownMembers($this->client->customers->list($next));
        }

        if ([] === $page->items) {
            self::markTestIncomplete('The sandbox has no Customer to read.');
        }

        $customer = $page->items[0];
        self::assertInstanceOf(Customer::class, $customer);
        $include = new RetrieveCustomerParams();
        $include->include = ['Wallet'];
        self::collectUnknownMembers($this->client->customers->retrieve($customer->id, $include));
        self::collectUnknownMembers($this->client->customers->listAddresses($customer->id));
        self::collectUnknownMembers($this->client->customers->listWalletMovements($customer->id));
        self::collectUnknownMembers($this->client->paymentMethods->list($customer->id));

        try {
            self::collectUnknownMembers($this->client->customers->retrievePrepaidBalance($customer->id, new RetrieveCustomerPrepaidBalanceParams(new \DateTimeImmutable())));
        } catch (ApiException $exception) {
            self::assertLessThan(500, $exception->getStatusCode(), $exception->getMessage());
        }
    }

    public function testReadsTheStores(): void
    {
        $params = new ListStoreParams();
        $params->include = ['conceptStore', 'storeArea', 'status'];
        $page = $this->client->stores->list($params);
        self::collectUnknownMembers($page);
        if ([] !== $page->items) {
            self::collectUnknownMembers($this->client->stores->retrieve($page->items[0]->id));
        }

        self::collectUnknownMembers($this->client->conceptStores->list());
        self::collectUnknownMembers($this->client->storeAreas->list());
        self::collectUnknownMembers($this->client->terminals->list());
        self::addToAssertionCount(1);
    }

    public function testReadsTheVolumesReport(): void
    {
        $report = $this->client->reports->volumes(new VolumesReportParams(new \DateTimeImmutable('-30 days'), new \DateTimeImmutable()));

        self::assertNotNull($report->totals);
        self::collectUnknownMembers($report);
    }

    public function testAMalformedIdIsRejectedWithTheParameterInError(): void
    {
        try {
            $this->client->payments->retrieve('pay_not_an_id');
            self::fail('A malformed id was accepted.');
        } catch (BadRequestException $exception) {
            self::assertSame('VALIDATION_ERROR', $exception->getErrorCode());
            self::assertNotNull($exception->getParameter());
        }
    }

    public function testAWrongKeyIsRejected(): void
    {
        $client = new VoucherlyClient(['apiKey' => 'sk_sand_not_a_key']);

        try {
            $client->paymentGateways->list();
            self::fail('A wrong key was accepted.');
        } catch (ApiException $exception) {
            self::assertSame(401, $exception->getStatusCode());
        }
    }

    /**
     * @param mixed $value
     */
    private static function collectUnknownMembers($value): void
    {
        if ($value instanceof VoucherlyObject) {
            foreach (array_keys($value->getExtensionData()) as $member) {
                self::$unknownMembers[] = (new \ReflectionClass($value))->getShortName() . '.' . $member;
            }
            foreach (get_object_vars($value) as $property) {
                self::collectUnknownMembers($property);
            }
        } elseif (\is_array($value)) {
            foreach ($value as $item) {
                self::collectUnknownMembers($item);
            }
        }
    }
}
