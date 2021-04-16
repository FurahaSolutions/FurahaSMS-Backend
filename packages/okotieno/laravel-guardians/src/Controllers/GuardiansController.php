<?php

namespace Okotieno\Guardians\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Okotieno\GuardianAdmissions\Requests\User\CreateGuardianRequest;

class GuardiansController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param CreateStudentRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(CreateGuardianRequest $request)
  {


  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($id)
  {
    return User::find($id);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param LibraryBookAuthor $libraryBookAuthor
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request)
  {

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id)
  {
    //
  }
}
