<?php

namespace VoucherlyApi\Enum;

/**
 * Where you won the Customer. Voucherly records the other points of acquisition, such as the table or a Payment link, itself, and refuses them here with code `ORIGIN_NOT_DECLARABLE`. Defaults to `Api`.
 */
final class CustomerOriginRequest
{
    public const SITE = 'Site';

    public const STAFF = 'Staff';

    public const IMPORT = 'Import';

    public const API = 'Api';
}
