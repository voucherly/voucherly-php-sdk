<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

/**
 * @template T of VoucherlyObject
 */
class Page extends VoucherlyObject
{
    /**
     * An array containing the actual response elements, paginated by any request parameters.
     *
     * @var list<T>
     */
    public array $items = [];

    public ?Pagination $pagination;

    /**
     * @internal
     *
     * @param array<string, mixed>          $data
     * @param class-string<VoucherlyObject> $itemClass
     *
     * @return static
     */
    public static function constructPage(array $data, string $itemClass)
    {
        return static::hydrateWithItems($data, $itemClass);
    }

    protected static function types(): array
    {
        return ['pagination' => Pagination::class];
    }
}
