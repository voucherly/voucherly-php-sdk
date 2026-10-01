<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Page;
use VoucherlyApi\Model\PaymentMethod;
use VoucherlyApi\Request\ListCustomerPaymentMethodParams;

final class PaymentMethodService extends AbstractService
{
    /**
     * List a Customer's PaymentMethods.
     * Returns a paginated list of the PaymentMethods saved on the Customer, the most recent first.
     *
     * @return Page<PaymentMethod>
     */
    public function list(string $customerId, ?ListCustomerPaymentMethodParams $params = null): Page
    {
        return Page::constructPage($this->requestJson('GET', self::path('/v1/customers/%s/payment_methods', $customerId), null, $params), PaymentMethod::class);
    }

    /**
     * Delete a Customer's PaymentMethod.
     */
    public function delete(string $customerId, string $paymentMethodId): void
    {
        $this->requestNoContent('DELETE', self::path('/v1/customers/%s/payment_methods/%s', $customerId, $paymentMethodId));
    }
}
