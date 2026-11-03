<?php

namespace VoucherlyApi\Enum;

/**
 * The channel of a marketing consent. Absent for profiling, which has no channel.
 */
final class ConsentChannel
{
    public const EMAIL = 'Email';

    public const SMS = 'Sms';

    public const WHATSAPP = 'Whatsapp';
}
