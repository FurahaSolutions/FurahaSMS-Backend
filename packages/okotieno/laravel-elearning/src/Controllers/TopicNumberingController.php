<?php

namespace Okotieno\ELearning\Controllers;


use App\Http\Controllers\Controller;
use Okotieno\ELearning\Models\TopicNumberStyle;

class TopicNumberingController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    return response()->json(TopicNumberStyle::all());
  }

}

