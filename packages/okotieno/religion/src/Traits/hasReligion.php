<?php


namespace Okotieno\Religion\Traits;


use Okotieno\Religion\Models\Religion;

trait hasReligion
{
  public function religion()
  {
    return $this->belongsto(Religion::class);
  }

  public function getReligionNameAttribute()
  {
    return $this->religion ? $this->religion->name : null;
  }
}
