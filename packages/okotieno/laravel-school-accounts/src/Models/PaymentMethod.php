<?php

namespace Okotieno\SchoolAccounts\Models;

use App\Traits\HasActiveProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolAccounts\Database\Factories\PaymentMethodFactory;

class PaymentMethod extends Model
{
    use HasActiveProperty, SoftDeletes, HasFactory;
    public $timestamps = false;
    protected $hidden = ['deleted_at'];
    protected $fillable = ['name'];
    protected static function newFactory()
    {
      return PaymentMethodFactory::new();
    }
}
