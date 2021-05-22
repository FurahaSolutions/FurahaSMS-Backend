<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 4/6/2020
 * Time: 7:24 PM
 */

namespace App\Traits;


trait HasActiveProperty
{
  public static function scopeActive($query)
  {
    return $query->where('active', true);
  }
}
