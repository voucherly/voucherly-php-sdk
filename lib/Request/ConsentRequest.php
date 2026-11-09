<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\ConsentChannel;
use VoucherlyApi\Enum\ConsentPurpose;
use VoucherlyApi\Enum\ConsentSource;
use VoucherlyApi\VoucherlyObject;

/**
 * One choice of the Customer as you collected it. IP address and user agent are the Customer's, not your server's.
 */
class ConsentRequest extends VoucherlyObject
{
    /**
     * What the consent is for. `Marketing` is receiving promotional messages on a channel; `Profiling` is the analysis of the purchase history to tailor offers.
     * Required.
     * One of the {@see ConsentPurpose} constants.
     */
    public ?string $purpose;

    /**
     * Required for a marketing consent, and not allowed for profiling.
     * One of the {@see ConsentChannel} constants.
     */
    public ?string $channel;

    /**
     * True when the Customer gives the consent, false when they refuse or revoke it.
     * Required.
     */
    public ?bool $granted;

    /**
     * When the Customer made the choice. It cannot be in the future. A choice older than the current one for the same purpose and channel is ignored.
     * Required.
     */
    public ?string $collectedAt;

    /**
     * Where the choice was collected. Defaults to `Other`.
     * One of the {@see ConsentSource} constants.
     */
    public ?string $source;

    /**
     * The text the Customer was shown. Required when `granted` is true.
     */
    public ?string $text;

    /**
     * The version of that text in your own system.
     */
    public ?string $version;

    /**
     * The IP address of the Customer when they made the choice.
     */
    public ?string $ipAddress;

    /**
     * The user agent of the Customer's browser when they made the choice.
     */
    public ?string $userAgent;
}
