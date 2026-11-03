<?php

namespace VoucherlyApi\Tests\Service;

use VoucherlyApi\Enum\CompanyInclude;
use VoucherlyApi\Model\Company;
use VoucherlyApi\Request\CreateCompanyRequest;
use VoucherlyApi\Request\ListCompanyParams;
use VoucherlyApi\Request\RetrieveCompanyParams;
use VoucherlyApi\Request\UpdateCompanyRequest;
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

    public function testCreateWithAReference(): void
    {
        $this->respondWithSample('create-company');
        $request = new CreateCompanyRequest();
        $request->name = 'Acme S.p.A.';
        $request->referenceId = 'company-318';

        $this->client->companies->create($request);

        $this->assertOperation('create-company', [], '', ['name' => 'Acme S.p.A.', 'referenceId' => 'company-318']);
    }

    public function testListByReference(): void
    {
        $this->respondWithSample('list-company');
        $params = new ListCompanyParams();
        $params->referenceId = 'company-318';

        $this->client->companies->list($params);

        $this->assertOperation('list-company', [], 'referenceId=company-318');
    }

    public function testUpdateSendsOnlyTheAssignedFieldsAndAnExplicitNull(): void
    {
        $json = $this->respondWithSample('update-company');
        $request = new UpdateCompanyRequest();
        $request->name = 'Acme S.r.l.';
        $request->referenceId = null;

        $company = $this->client->companies->update('ACME01', $request);

        $this->assertOperation('update-company', ['idOrCode' => 'ACME01'], '', ['name' => 'Acme S.r.l.', 'referenceId' => null]);
        self::assertReadsEveryMember($json, $company);
    }
}
