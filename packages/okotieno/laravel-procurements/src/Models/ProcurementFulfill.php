<?php

namespace Okotieno\Procurement\Models;


use Illuminate\Database\Eloquent\Model;

class ProcurementFulfill extends Model
{
  protected $fillable = [
    'procurement_tender_id',
    'entered_by',
    'fulfilled',
    'comment'
  ];
}
