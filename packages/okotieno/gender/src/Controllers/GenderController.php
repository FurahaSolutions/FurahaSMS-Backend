<?php

namespace Okotieno\Gender\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\Gender\Models\Gender;

class GenderController extends Controller
{
  public function index()
  {
    return response()->json(Gender::all());
  }
}
