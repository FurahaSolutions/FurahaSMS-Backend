<?php

namespace Okotieno\SchoolExams\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolExams\Models\ExamPaper;
use Okotieno\SchoolExams\Requests\ExamPaperDestroyRequest;
use Okotieno\SchoolExams\Requests\ExamPaperStoreRequest;
use Okotieno\SchoolExams\Requests\ExamPaperUpdateRequest;

class ExamPapersController extends Controller
{
  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    $response = [];
    if ($request->self !== null) {
      foreach (User::find(auth()->id())->createdExamPapers as $examPaper) {
        $response[] = [
          'id' => $examPaper->id,
          'name' => $examPaper->name,
          'unit_level_name' => $examPaper->unitLevel->name
        ];
      }

    }
    if ($request->latest != null) {
      $response = ExamPaper::latest()->limit($request->latest)->get();
    }
    return response()->json($response);
  }

  /**
   * @param ExamPaper $examPaper
   * @return JsonResponse
   */

  public function show(ExamPaper $examPaper)
  {
    $examPaper->instructions;
    $examPaper->questions;
    $examPaper->unit_level_name;
    return response()->json($examPaper);
  }

  public function store(ExamPaperStoreRequest $request): JsonResponse
  {

    $newPaper = ExamPaper::create([
      'name' => $request->name,
      'unit_level_id' => $request->unit_level_id,
      'created_by' => auth()->id()
    ]);
    if ($request->instructions != null) {
      foreach ($request->instructions as $instruction) {
        $position = null;
        if (key_exists('position', $instruction)) {
          $position = $instruction['position'];
        }
        $newPaper->instructions()->create([
          'description' => $instruction['description'],
          'position' => $position,
        ]);
      }
    }
    return response()->json([
      'saved' => true,
      'message' => 'Financial Plan Successfully saved',
      'data' => $newPaper
    ])->setStatusCode(201);
  }

  public function update(ExamPaperUpdateRequest $request, ExamPaper $examPaper)
  {
    $examPaper->update($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Financial Plan Successfully saved',
      'data' => $examPaper
    ]);
  }

  /**
   * @param ExamPaperDestroyRequest $request
   * @param ExamPaper $examPaper
   * @return JsonResponse
   */
  public function destroy(ExamPaperDestroyRequest $request, ExamPaper $examPaper): JsonResponse
  {
    $examPaper->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Exam Paper Successfully deleted'
    ]);
  }
}
