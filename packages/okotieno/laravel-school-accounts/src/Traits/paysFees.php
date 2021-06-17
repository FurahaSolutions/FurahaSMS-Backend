<?php


namespace Okotieno\SchoolAccounts\Traits;
use Okotieno\SchoolAccounts\Models\FeePayment;

trait paysFees
{
    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }

}
