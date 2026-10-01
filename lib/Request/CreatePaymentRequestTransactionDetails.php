<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Gateway-specific details of the transaction. Only `POS` takes them today, to say which card reader must collect the payment: give exactly one of the three identifiers below. The Terminal is looked up across the whole merchant, so it must be paired and, for `referenceTerminalId`, carry that reference — otherwise the request fails with `RESOURCE_MISSING` and no Payment is created.
 */
class CreatePaymentRequestTransactionDetails extends VoucherlyObject
{
    /**
     * The ID of the Terminal in Voucherly, in the form `term_01kg2gestgepdbmsn7hs6bsrwp`.
     */
    public ?string $terminalId;

    /**
     * The ID the payment gateway gave the reader, as returned by the Terminals endpoint.
     */
    public ?string $externalTerminalId;

    /**
     * Your own reference for the reader, set on the Terminal from the Voucherly dashboard. Use it to address a reader by the code your cash register already knows, such as `00011.007`.
     */
    public ?string $referenceTerminalId;
}
