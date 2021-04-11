<?php


namespace Okotieno\AcademicYear\Traits;


use Okotieno\AcademicYear\Models\AcademicYear;

trait HasAcademicYear
{
  public function academicYears()
  {
    return $this->belongsToMany(AcademicYear::class);
  }

}
