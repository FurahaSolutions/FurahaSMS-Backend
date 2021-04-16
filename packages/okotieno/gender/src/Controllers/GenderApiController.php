<?php

namespace Okotieno\Gender\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\Gender\Models\Gender;

class GenderApiController extends Controller
{
  public function getAll()
  {
    return Gender::all();
  }
}
