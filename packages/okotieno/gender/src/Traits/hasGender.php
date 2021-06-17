<?php


namespace Okotieno\Gender\Traits;


use Okotieno\Gender\Models\Gender;

trait hasGender
{
  public function gender()
  {
    return $this->belongsto(Gender::class);
  }

  public function getGenderNameAttribute()
  {
    return $this->gender ? $this->gender->name : null;
  }
}
