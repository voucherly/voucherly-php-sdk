<?php

namespace VoucherlyApi\Enum;

/**
 * What the PaymentLine is, which decides both how the checkout lays it out and whether meal vouchers can pay it:
 * - `NonFood`: goods that are not food, such as detergents or household items. Not payable with meal vouchers, and the default when the line declares nothing.
 * - `Food`: grocery food. The only type meal vouchers can pay.
 * - `Shipping`: shipping costs. The checkout always shows it after the products, whatever position you send it in.
 * - `AdditionalCharge`: a delivery service, a tip or any other accessory charge. Shown after the products, like `Shipping`.
 * - `PreauthorizationMargin`: the margin authorised on top of the estimated total, covering variable-weight items and released once the effective amount is confirmed. The checkout does not list it among the products: it counts towards the total, and an info icon next to it explains the difference. Send `product.name` and `product.variant` on that line to replace the default wording of that explanation. At confirmation it is released whether you send it back or not: send it with quantity `0` or leave it out, anything else fails with `MARGIN_NOT_CONFIRMABLE`. It is never printed on the documents for the customer.
 */
final class LineType
{
    public const NON_FOOD = 'NonFood';

    public const FOOD = 'Food';

    public const SHIPPING = 'Shipping';

    public const ADDITIONAL_CHARGE = 'AdditionalCharge';

    public const PREAUTHORIZATION_MARGIN = 'PreauthorizationMargin';
}
