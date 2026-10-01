<?php

namespace VoucherlyApi\Enum;

/**
 * What to do when the amount to charge is below the minimum a payment gateway accepts — 0.50 EUR on most card gateways. The request is raised to that minimum, and this decides what happens to the part that exceeds what is due: with `None` it stays with you, with `Credit` it is credited to the customer's wallet. With `HideGateways` the amount is not raised at all: the gateway is not offered on the Checkout page, and a transaction requested on it fails with `AMOUNT_BELOW_MINIMUM`. Use `HideGateways` when nobody can pick another method, such as a charge on a terminal. If you don't specify anything the default Merchant configuration will be used.
 */
final class ExceedingAmountMode
{
    public const NONE = 'None';

    public const CREDIT = 'Credit';

    public const HIDE_GATEWAYS = 'HideGateways';
}
