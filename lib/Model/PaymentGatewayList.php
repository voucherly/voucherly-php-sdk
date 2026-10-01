<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * Response object containing a list of available PaymentGateways.
 */
class PaymentGatewayList extends VoucherlyObject
{
    /**
     * An array of PaymentGateway objects representing all available PaymentGateways.
     *
     * @var null|list<PaymentGateway>
     */
    public ?array $items;

    protected static function types(): array
    {
        return [
            'items' => [PaymentGateway::class],
        ];
    }
}
