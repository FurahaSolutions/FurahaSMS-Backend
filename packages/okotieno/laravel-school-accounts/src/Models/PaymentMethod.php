<?php

namespace Okotieno\SchoolAccounts\Models;

use App\Traits\HasActiveProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasActiveProperty;
    use SoftDeletes;
    public $timestamps = false;
    protected $hidden = ['deleted_at'];
    protected $fillable = [
        'name',
    ];


}
