<?php

namespace VoucherlyApi\Model;

use VoucherlyApi\VoucherlyObject;

class VolumesReport extends VoucherlyObject
{
    /**
     * An array containing the per-PaymentGateway breakdown rows, paginated by any request parameters.
     *
     * @var null|list<RevenuesDetails>
     */
    public ?array $items;

    public ?Pagination $pagination;

    public ?RevenuesTotals $totals;

    protected static function types(): array
    {
        return [
            'items' => [RevenuesDetails::class],
            'pagination' => Pagination::class,
            'totals' => RevenuesTotals::class,
        ];
    }
}
