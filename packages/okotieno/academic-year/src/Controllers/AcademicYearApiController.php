<?php

namespace Okotieno\AcademicYear\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\AcademicYear\Models\AcademicYear;

class AcademicYearApiController extends Controller
{
  public function getAll()
  {
    return [];
  }

  public function semesters(AcademicYear $academicYear)
  {
    return $academicYear->semesters;
  }
}
