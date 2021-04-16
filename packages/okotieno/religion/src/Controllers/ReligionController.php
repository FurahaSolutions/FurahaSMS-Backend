<?php

namespace Okotieno\Religion\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\Religion\Models\Religion;

class ReligionController extends Controller
{
  public function index()
  {
    return response()->json(Religion::all());
  }
}
