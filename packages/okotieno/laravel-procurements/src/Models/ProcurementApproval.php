<?php


namespace Okotieno\Procurement\Models;


use Illuminate\Database\Eloquent\Model;

class ProcurementApproval extends Model
{
  protected $fillable = [
    'approved',
    'approved_at',
    'approved_by'
  ];
}
