<?php

namespace App\Traits;


trait HasActiveProperty
{
  public static function scopeActive($query)
  {
    return $query->where('active', true);
  }
}
