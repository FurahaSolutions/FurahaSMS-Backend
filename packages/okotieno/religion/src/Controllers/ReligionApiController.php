<?php

namespace Okotieno\Religion\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\Religion\Models\Religion;

class ReligionApiController extends Controller
{
  public function getAll()
  {
    return Religion::all();
  }
}
