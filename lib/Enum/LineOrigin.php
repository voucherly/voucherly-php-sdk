<?php

namespace VoucherlyApi\Enum;

/**
 * Where the PaymentLine comes from:
 * - `Standard`: the line was part of the original order.
 * - `TableUpsell`: the line was added by an upselling proposal accepted while paying at the table.
 * - `LiveOffer`: the product of the line was added to the cart from an upselling popup of Voucherly Marketing, in the session named by the `voucherly_marketing_session` metadata. Voucherly sets it when the payment is created.
 */
final class LineOrigin
{
    public const STANDARD = 'Standard';

    public const TABLE_UPSELL = 'TableUpsell';

    public const LIVE_OFFER = 'LiveOffer';
}
