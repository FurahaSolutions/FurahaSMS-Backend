<?php

namespace Okotieno\Procurement\Models;

use Okotieno\Procurement\Database\Factories\ProcurementItemsCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementItemsCategory extends Model
{
  use HasFactory;
  protected static function newFactory()
  {
    return ProcurementItemsCategoryFactory::new();
  }

}
