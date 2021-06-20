<?php

namespace Okotieno\SchoolAccounts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolAccounts\Database\Factories\FinancialCostItemFactory;

class FinancialCostItem extends Model
{
  use SoftDeletes, HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'name',
  ];

  protected static function newFactory()
  {
    return FinancialCostItemFactory::new();
  }
}
