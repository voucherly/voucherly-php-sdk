<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\TerminalStatus;
use VoucherlyApi\VoucherlyObject;

/**
 * A POS Terminal registered with a PaymentGateway and optionally bound to a Store.
 */
class Terminal extends VoucherlyObject
{
    /**
     * Unique identifier for the Terminal.
     */
    public ?string $id;

    /**
     * The ID of the PaymentGatewayAccount this Terminal belongs to.
     */
    public ?string $paymentGatewayAccountId;

    public ?PaymentGatewayEssential $paymentGateway;

    /**
     * The ID of the Store this Terminal is bound to, when applicable.
     */
    public ?string $storeId;

    /**
     * The name of the Store this Terminal is bound to, when applicable.
     */
    public ?string $storeName;

    /**
     * The identifier assigned to the Terminal by the PaymentGateway provider.
     */
    public ?string $externalId;

    /**
     * The identifier you assigned to the Terminal to reconcile it with your own systems.
     */
    public ?string $referenceId;

    /**
     * The display name of the Terminal.
     */
    public ?string $name;

    /**
     * The brand of the physical device.
     */
    public ?string $brand;

    /**
     * The model of the physical device.
     */
    public ?string $model;

    /**
     * The serial number of the physical device.
     */
    public ?string $serialNumber;

    /**
     * One of the {@see TerminalStatus} constants.
     */
    public ?string $status;

    /**
     * The UTC timestamp when the Terminal was created in the PaymentGateway provider system.
     */
    public ?string $externalCreatedAt;

    /**
     * The UTC timestamp when the Terminal was last updated in the PaymentGateway provider system.
     */
    public ?string $externalUpdatedAt;

    protected static function types(): array
    {
        return [
            'paymentGateway' => PaymentGatewayEssential::class,
        ];
    }
}
