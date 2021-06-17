<?php

namespace Okotieno\Procurement\Models;


use Illuminate\Database\Eloquent\Model;

class ProcurementVendorContact extends Model
{
  protected $fillable = [
    'name',
    'is_phone',
    'is_email',
    'value',
    'procurement_vendor_id'
  ];
}
