<?php

namespace VoucherlyApi\Payment;

class CreatePaymentRequest
{
    public string $mode = 'Payment';
    public ?string $referenceId = null;
    public ?string $customerPaymentMethodId = null;
    public ?string $customerId = null;
    public string $customerEmail = '';
    public string $customerFirstName = '';
    public string $customerLastName = '';
    /**
     * @var string
     */
    public $redirectOkUrl = '';
    /**
     * @var string
     */
    public $redirectKoUrl = '';
    public ?string $callbackUrl = null;
    public ?string $language = null;
    public ?string $country = null;
    public ?string $shippingAddress = null;

    public $metadata = [];

    /**
     * @var CreatePaymentRequestLine[]
     */
    public $lines = [];
    /**
     * @var CreatePaymentRequestDiscount[]
     */
    public $discounts = [];
}
