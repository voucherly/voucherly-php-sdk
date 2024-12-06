<?php

namespace VoucherlyApi\Payment;

class CreatePaymentRequestLine
{
    public int $quantity = 0;

    public ?int $priceId;
    public int $unitAmount = 0;
    public int $unitDiscountAmount = 0;
    public int $discountAmount = 0;

    public ?float $taxRate = null;

    public ?int $productId;
    public string $productName = '';
    public string $productDescription = '';
    public string $productImage = '';


    public bool $isFood = false;
}
