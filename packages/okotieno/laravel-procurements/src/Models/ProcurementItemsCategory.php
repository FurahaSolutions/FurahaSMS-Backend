<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 1/10/2020
 * Time: 8:05 PM
 */

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
