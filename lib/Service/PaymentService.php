<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Model\Payment;
use VoucherlyApi\Request\ConfirmPaymentRequest;
use VoucherlyApi\Request\CreatePaymentRequest;
use VoucherlyApi\Request\RefundPaymentRequest;
use VoucherlyApi\Request\RetrievePaymentParams;

final class PaymentService extends AbstractService
{
    /**
     * Create a Payment.
     */
    public function create(CreatePaymentRequest $request): Payment
    {
        return Payment::constructFrom($this->requestJson('POST', '/v1/payments', $request));
    }

    /**
     * Retrieve a Payment.
     */
    public function retrieve(string $id, ?RetrievePaymentParams $params = null): Payment
    {
        return Payment::constructFrom($this->requestJson('GET', self::path('/v1/payments/%s', $id), null, $params));
    }

    /**
     * Confirm a Payment.
     * Captures a `Paid` Payment.
     * Send the lines actually delivered, or the final and food amounts, and Voucherly decides how much to capture, void or give back on each transaction.
     * The Payment stays `Paid` until every operation has succeeded.
     * When a payment gateway fails midway, the response lists the operations already done and the same request can be sent again: it settles only what is left.
     */
    public function confirm(string $id, ?ConfirmPaymentRequest $request = null): Payment
    {
        return Payment::constructFrom($this->requestJson('POST', self::path('/v1/payments/%s/confirm', $id), $request));
    }

    /**
     * Refund a Payment.
     */
    public function refund(string $id, ?RefundPaymentRequest $request = null): Payment
    {
        return Payment::constructFrom($this->requestJson('POST', self::path('/v1/payments/%s/refund', $id), $request));
    }

    /**
     * Void a Payment.
     * Voids a Payment that has not yet been paid.
     * Only Payments in `Requested` status can be voided.
     */
    public function void(string $id): Payment
    {
        return Payment::constructFrom($this->requestJson('POST', self::path('/v1/payments/%s/void', $id)));
    }

    /**
     * Download a Payment receipt.
     * Downloads the fiscal sale receipt issued for a Payment, as a PDF document.
     *
     * @return string the bytes of the PDF
     */
    public function downloadReceipt(string $id): string
    {
        return $this->requestBytes(self::path('/v1/payments/%s/receipt', $id), 'application/pdf');
    }

    /**
     * Download a Payment refund receipt.
     * Downloads the fiscal void/refund receipt issued for a Payment, as a PDF document.
     *
     * @return string the bytes of the PDF
     */
    public function downloadRefundReceipt(string $id): string
    {
        return $this->requestBytes(self::path('/v1/payments/%s/refund_receipt', $id), 'application/pdf');
    }
}
