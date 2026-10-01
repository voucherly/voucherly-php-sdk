<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\VolumesReport;
use VoucherlyApi\Request\VolumesReportParams;

final class ReportService extends AbstractService
{
    /**
     * Volumes.
     * Returns aggregated turnover for the given date range, broken down by PaymentGateway and optionally by Store and/or Company, together with the grand totals across all rows.
     * This is the same data shown in the Volumes report of the Voucherly Dashboard.
     */
    public function volumes(VolumesReportParams $params): VolumesReport
    {
        return VolumesReport::constructFrom($this->requestJson('GET', '/v1/reports/volumes', null, $params));
    }
}
