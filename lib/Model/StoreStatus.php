<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\StoreStatusValue;
use VoucherlyApi\VoucherlyObject;

/**
 * The outcome of the periodic health checks Voucherly runs against the Store POS connection.
 */
class StoreStatus extends VoucherlyObject
{
    /**
     * The current health of the Store.
     * One of the {@see StoreStatusValue} constants.
     */
    public ?string $status;

    /**
     * The UTC timestamp when the Store entered the current status.
     */
    public ?string $currentStatusFromUtc;

    /**
     * The UTC timestamp of the most recent health check.
     */
    public ?string $lastCheckedAtUtc;

    /**
     * The UTC timestamp of the most recent successful health check.
     */
    public ?string $lastSuccessAtUtc;

    /**
     * The number of consecutive failed health checks.
     */
    public ?int $consecutiveKos;

    /**
     * The number of consecutive successful health checks.
     */
    public ?int $consecutiveOks;

    /**
     * The response time of the most recent health check, in milliseconds.
     */
    public ?int $lastResponseTimeMs;
}
