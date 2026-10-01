<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Enum\CompanyInclude;
use VoucherlyApi\Model\Company;
use VoucherlyApi\Request\CreateCompanyRequest;
use VoucherlyApi\Request\ListCompanyParams;
use VoucherlyApi\Request\RetrieveCompanyParams;
use VoucherlyApi\Tests\Support\ServiceTestCase;

final class CompanyServiceTest extends ServiceTestCase
{
    public function testCreate(): void
    {
        $json = $this->respondWithSample('create-company');
        $request = new CreateCompanyRequest();
        $request->name = 'Acme S.p.A.';
        $request->joinCode = 'ACME01';

        $company = $this->client->companies->create($request);

        $this->assertOperation('create-company', [], '', ['name' => 'Acme S.p.A.', 'joinCode' => 'ACME01']);
        self::assertReadsEveryMember($json, $company);
    }

    public function testList(): void
    {
        $json = $this->respondWithSample('list-company');
        $params = new ListCompanyParams();
        $params->length = 20;
        $params->start = 'cmp_next';

        $page = $this->client->companies->list($params);

        $this->assertOperation('list-company', [], 'length=20&start=cmp_next');
        self::assertInstanceOf(Company::class, $page->items[0]);
        self::assertReadsEveryMember($json, $page);
    }

    public function testListWithoutParameters(): void
    {
        $this->respondWithSample('list-company');

        $this->client->companies->list();

        $this->assertOperation('list-company');
    }

    public function testRetrieve(): void
    {
        $json = $this->respondWithSample('retrieve-company');
        $params = new RetrieveCompanyParams();
        $params->include = [CompanyInclude::ADDRESSES];

        $company = $this->client->companies->retrieve('ACME01', $params);

        $this->assertOperation('retrieve-company', ['idOrCode' => 'ACME01'], 'include=Addresses');
        self::assertReadsEveryMember($json, $company);
    }
}
