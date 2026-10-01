<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\PaymentGatewayInclude;

final class ListPaymentGatewayParams extends RequestParams
{
    /**
     * Returns the PaymentGateways of the configuration the Store resolves to. Ignored when `paymentGatewayConfigurationId` is given.
     */
    public ?string $storeId;

    /**
     * Returns the PaymentGateways of one specific configuration, in the form `pgc_01kg2gestgepdbmsn7hs6bsrwp`. With neither this nor `storeId`, the default configuration of the merchant is used.
     */
    public ?string $paymentGatewayConfigurationId;

    /**
     * Specifies whether to return all payment gateways or only those that are enabled. If true, returns all payment gateways regardless of their enabled status. If false or omitted, returns only enabled payment gateways.
     */
    public ?bool $all;

    /**
     * An array of nested objects to include in the response.
     * Each item is one of the {@see PaymentGatewayInclude} constants.
     *
     * @var null|list<string>
     */
    public ?array $include;
}
