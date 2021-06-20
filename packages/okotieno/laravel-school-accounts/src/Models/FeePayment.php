<?php

namespace Okotieno\SchoolAccounts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolAccounts\Database\Factories\FeePaymentFactory;

class FeePayment extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'amount',
        'ref',
        'payment_method_id',
        'transaction_date'
    ];
    protected $hidden = ['deleted_at'];
    protected $appends = ['payment_method_name'];

    protected static function newFactory()
    {
      return FeePaymentFactory::new();
    }

  public function getPaymentMethodNameAttribute(){
        return PaymentMethod::find($this->payment_method_id)->name;
    }
}
