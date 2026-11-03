<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\Enum\ConsentChannel;
use VoucherlyApi\Enum\ConsentPurpose;
use VoucherlyApi\VoucherlyObject;

/**
 * The current choice of a Customer for one purpose, and for marketing one channel.
 */
class Consent extends VoucherlyObject
{
    /**
     * What the consent is for. `Marketing` is receiving promotional messages; `Profiling` is the analysis of the purchase history to tailor offers.
     * One of the {@see ConsentPurpose} constants.
     */
    public ?string $purpose;

    /**
     * The channel of a marketing consent. Absent for profiling, which has no channel.
     * One of the {@see ConsentChannel} constants.
     */
    public ?string $channel;

    /**
     * Whether the consent is given. False means the Customer revoked it, which for email is also the opposition to promotional email sent to past buyers.
     */
    public ?bool $granted;

    /**
     * When the Customer made this choice, in UTC.
     */
    public ?string $collectedAt;

    /**
     * The version of the consent text the Customer was shown.
     */
    public ?string $version;
}
