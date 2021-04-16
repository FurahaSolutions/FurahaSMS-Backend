<?php

namespace Okotieno\GuardianAdmissions\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\StudentAdmissions\Models\Student;
use Okotieno\GuardianAdmissions\Requests\User\CreateGuardianRequest;
use App\Models\User;
use Illuminate\Http\Request;

class StudentGuardianController extends Controller
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

        $student = Student::where('student_school_id_number', $request->student_id)->first();
        $user = $student->createGuardian($request);
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param LibraryBookAuthor $libraryBookAuthor
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
    }
}
