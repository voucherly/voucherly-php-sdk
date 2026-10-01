<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Customer;
use VoucherlyApi\Model\CustomerAddress;
use VoucherlyApi\Model\CustomerPrepaidBalance;
use VoucherlyApi\Model\CustomerWalletMovement;
use VoucherlyApi\Model\Page;
use VoucherlyApi\Request\CreateCustomerRequest;
use VoucherlyApi\Request\CustomerAddressRequest;
use VoucherlyApi\Request\ListCustomerAddressParams;
use VoucherlyApi\Request\ListCustomerParams;
use VoucherlyApi\Request\ListCustomerWalletMovementParams;
use VoucherlyApi\Request\RetrieveCustomerParams;
use VoucherlyApi\Request\RetrieveCustomerPrepaidBalanceParams;
use VoucherlyApi\Request\UpdateCustomerRequest;

final class CustomerService extends AbstractService
{
    /**
     * Create a Customer.
     */
    public function create(CreateCustomerRequest $request): Customer
    {
        return Customer::constructFrom($this->requestJson('POST', '/v1/customers', $request));
    }

    /**
     * List all Customers.
     *
     * @return Page<Customer>
     */
    public function list(?ListCustomerParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', '/v1/customers', null, $params), Customer::class);
    }

    /**
     * Retrieve a Customer.
     */
    public function retrieve(string $id, ?RetrieveCustomerParams $params = null): Customer
    {
        return Customer::constructFrom($this->requestJson('GET', self::path('/v1/customers/%s', $id), null, $params));
    }

    /**
     * Update a Customer.
     * Updates the specified customer by setting the values of the parameters passed.
     * Any parameters not provided will be left unchanged.
     */
    public function update(string $id, UpdateCustomerRequest $request): Customer
    {
        return Customer::constructFrom($this->requestJson('POST', self::path('/v1/customers/%s', $id), $request));
    }

    /**
     * Retrieve a Customer's prepaid balance.
     * Returns the prepaid balance available to a Customer for a given date, based on the prepaid policy configured on the Customer's Company.
     */
    public function retrievePrepaidBalance(string $customerId, RetrieveCustomerPrepaidBalanceParams $params): CustomerPrepaidBalance
    {
        return CustomerPrepaidBalance::constructFrom($this->requestJson('GET', self::path('/v1/customers/%s/prepaid', $customerId), null, $params));
    }

    /**
     * List a Customer's wallet movements.
     * Returns a paginated list of wallet movements (credits, debits, refunds, adjustments) recorded on the Customer's wallet.
     *
     * @return Page<CustomerWalletMovement>
     */
    public function listWalletMovements(string $id, ?ListCustomerWalletMovementParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', self::path('/v1/customers/%s/wallet/movements', $id), null, $params), CustomerWalletMovement::class);
    }

    /**
     * List a Customer's Addresses.
     * Returns a paginated list of the Addresses of the Customer, the most recent first.
     *
     * @return Page<CustomerAddress>
     */
    public function listAddresses(string $customerId, ?ListCustomerAddressParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', self::path('/v1/customers/%s/addresses', $customerId), null, $params), CustomerAddress::class);
    }

    /**
     * Create a Customer's Address.
     */
    public function createAddress(string $customerId, CustomerAddressRequest $request): CustomerAddress
    {
        return CustomerAddress::constructFrom($this->requestJson('POST', self::path('/v1/customers/%s/addresses', $customerId), $request));
    }

    /**
     * Retrieve a Customer's Address.
     */
    public function retrieveAddress(string $customerId, string $addressId): CustomerAddress
    {
        return CustomerAddress::constructFrom($this->requestJson('GET', self::path('/v1/customers/%s/addresses/%s', $customerId, $addressId)));
    }

    /**
     * Update a Customer's Address.
     */
    public function updateAddress(string $customerId, string $addressId, CustomerAddressRequest $request): CustomerAddress
    {
        return CustomerAddress::constructFrom($this->requestJson('PUT', self::path('/v1/customers/%s/addresses/%s', $customerId, $addressId), $request));
    }

    /**
     * Delete a Customer's Address.
     */
    public function deleteAddress(string $customerId, string $addressId): void
    {
        $this->requestNoContent('DELETE', self::path('/v1/customers/%s/addresses/%s', $customerId, $addressId));
    }
}
