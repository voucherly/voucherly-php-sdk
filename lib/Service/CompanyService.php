<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Company;
use VoucherlyApi\Model\Page;
use VoucherlyApi\Request\CreateCompanyRequest;
use VoucherlyApi\Request\ListCompanyParams;
use VoucherlyApi\Request\RetrieveCompanyParams;
use VoucherlyApi\Request\UpdateCompanyRequest;

final class CompanyService extends AbstractService
{
    /**
     * Create a Company.
     */
    public function create(CreateCompanyRequest $request): Company
    {
        return Company::constructFrom($this->requestJson('POST', '/v1/companys', $request));
    }

    /**
     * List all Companies.
     *
     * @return Page<Company>
     */
    public function list(?ListCompanyParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', '/v1/companys', null, $params), Company::class);
    }

    /**
     * Retrieve a Company.
     *
     * @param string $idOrCode the unique identifier (ID) or join code of the Company to retrieve
     */
    public function retrieve(string $idOrCode, ?RetrieveCompanyParams $params = null): Company
    {
        return Company::constructFrom($this->requestJson('GET', self::path('/v1/companys/%s', $idOrCode), null, $params));
    }

    /**
     * Update a Company.
     * Updates the specified company by setting the values of the parameters passed.
     * Any parameters not provided will be left unchanged.
     *
     * @param string $idOrCode the unique identifier (ID) or join code of the Company to update
     */
    public function update(string $idOrCode, UpdateCompanyRequest $request): Company
    {
        return Company::constructFrom($this->requestJson('POST', self::path('/v1/companys/%s', $idOrCode), $request));
    }
}
