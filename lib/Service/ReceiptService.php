<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Receipt;

final class ReceiptService extends AbstractService
{
    /**
     * Retrieve a Receipt.
     */
    public function retrieve(string $id): Receipt
    {
        return Receipt::constructFrom($this->requestJson('GET', self::path('/v1/receipts/%s', $id)));
    }

    /**
     * Download a Receipt.
     * Downloads the fiscal Receipt as a PDF document.
     *
     * @return string the bytes of the PDF
     */
    public function download(string $id): string
    {
        return $this->requestBytes(self::path('/v1/receipts/%s/download', $id), 'application/pdf');
    }
}
