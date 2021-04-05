<?php


namespace Okotieno\AcademicYear\Traits;


use Okotieno\AcademicYear\Models\Holiday;

trait HasHoliday
{
  public function holidays() {
    return $this->belongsToMany(Holiday::class)->withPivot(
      ['confirmed', 'date']
    );
  }

}
