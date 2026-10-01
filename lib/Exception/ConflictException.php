<?php

namespace VoucherlyApi\Exception;

/**
 * 409 Conflict.
 */
class ConflictException extends ApiException
{
    /**
     * The status of the Payment that prevented the operation, one of the Enum\PaymentStatus constants.
     */
    public function getPaymentStatus(): ?string
    {
        $value = $this->getExtensions()['paymentStatus'] ?? null;

        return \is_string($value) ? $value : null;
    }
}
