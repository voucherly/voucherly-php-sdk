<?php

namespace VoucherlyApi\Tests\Support;

use VoucherlyApi\VoucherlyObject;

final class SampleObject extends VoucherlyObject
{
    public ?string $name;
    public ?int $quantity;
    public ?float $rate;
    public ?bool $active;

    /** @var null|array<string, string> */
    public ?array $metadata;
    public ?\DateTimeInterface $day;
    public ?\DateTimeInterface $moment;
    public ?SampleChild $child;

    /** @var null|list<SampleChild> */
    public ?array $children;
    public ?string $kind;

    protected static function types(): array
    {
        return [
            'metadata' => 'map',
            'day' => 'date',
            'child' => SampleChild::class,
            'children' => [SampleChild::class],
        ];
    }

    protected static function jsonNames(): array
    {
        return ['kind' => '$type'];
    }
}
