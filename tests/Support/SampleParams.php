<?php

namespace VoucherlyApi\Tests\Support;

use VoucherlyApi\Request\RequestParams;

final class SampleParams extends RequestParams
{
    public ?string $name;
    public ?bool $isActive;

    /** @var null|list<string> */
    public ?array $include;
    public ?\DateTimeInterface $fromDate;
    public ?\DateTimeInterface $date;
    public ?int $waitTime;
    public ?int $length;

    protected static function headers(): array
    {
        return ['waitTime' => 'Voucherly-Wait-Time'];
    }

    protected static function types(): array
    {
        return ['date' => 'date'];
    }
}
