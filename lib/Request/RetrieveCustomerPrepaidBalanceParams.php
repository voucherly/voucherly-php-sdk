<?php

namespace VoucherlyApi\Request;

final class RetrieveCustomerPrepaidBalanceParams extends RequestParams
{
    /**
     * The date for which the prepaid balance must be evaluated, in `YYYY-MM-DD` format.
     */
    public \DateTimeInterface $date;

    public function __construct(\DateTimeInterface $date)
    {
        $this->date = $date;
    }

    protected static function types(): array
    {
        return ['date' => 'date'];
    }
}
