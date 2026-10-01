<?php

namespace VoucherlyApi\Tests\Support;

use VoucherlyApi\VoucherlyObject;

final class SampleChild extends VoucherlyObject
{
    public ?string $code;
    public ?int $size;
}
