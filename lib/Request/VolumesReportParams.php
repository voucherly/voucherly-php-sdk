<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\Enum\PaymentMode;
use VoucherlyApi\Enum\RevenuesGrouping;

final class VolumesReportParams extends RequestParams
{
    /**
     * Start of the reporting period (inclusive). Only the date part is considered.
     */
    public \DateTimeInterface $fromDate;

    /**
     * End of the reporting period (inclusive). Only the date part is considered. Must be greater than or equal to `fromDate`, and the range cannot exceed 366 days.
     */
    public \DateTimeInterface $toDate;

    /**
     * Filter by payment mode. When omitted, wallet recharges are excluded and only payments are aggregated.
     * One of the {@see PaymentMode} constants.
     */
    public ?string $paymentMode;

    /**
     * How rows are grouped. Repeat the parameter to group by more than one dimension (e.g. `groupBy=Store&groupBy=Company`). When omitted, rows are aggregated per PaymentGateway only.
     * Each item is one of the {@see RevenuesGrouping} constants.
     *
     * @var null|list<string>
     */
    public ?array $groupBy;

    /**
     * Filter by one or more PaymentGateway identifiers. Repeat the parameter to provide multiple values.
     *
     * @var null|list<string>
     */
    public ?array $paymentGatewayIds;

    /**
     * Filter by one or more Store identifiers. Repeat the parameter to provide multiple values.
     *
     * @var null|list<string>
     */
    public ?array $storeIds;

    /**
     * Filter by one or more Company identifiers. Repeat the parameter to provide multiple values.
     *
     * @var null|list<string>
     */
    public ?array $companyIds;

    /**
     * A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10.
     */
    public ?int $length;

    /**
     * A cursor for pagination across multiple pages of results. Don’t include this parameter on the first call. Use the `nextStart` value returned in a previous response to request subsequent results.
     */
    public ?string $start;

    public function __construct(\DateTimeInterface $fromDate, \DateTimeInterface $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
}
