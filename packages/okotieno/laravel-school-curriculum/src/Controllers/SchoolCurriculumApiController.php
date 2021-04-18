<?php

namespace Okotieno\SchoolCurriculum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\SchoolCurriculum\Models\UnitCategory;

class SchoolCurriculumApiController extends Controller
{
  public function get(Request $request)
  {
    if ($request->active == 1) {
      return UnitCategory::active()->get();
    }
    if ($request->id) {
      return UnitCategory::find($request->id);
    }
    return $this->getAll();
  }

  public function getAll()
  {
    return UnitCategory::all();
  }
}
