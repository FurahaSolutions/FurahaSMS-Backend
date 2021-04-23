<?php

namespace Okotieno\SchoolAccounts\Models;


use Okotieno\SchoolAccounts\Database\Factories\TuitionFeeFinancialPlanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionFeeFinancialPlan extends Model
{
  use HasFactory;

  protected $fillable = [
    'amount',
    'class_level_id',
    'unit_level_id',
    'semester_id'
  ];

  protected static function newFactory()
  {
    return TuitionFeeFinancialPlanFactory::new();
  }
}
