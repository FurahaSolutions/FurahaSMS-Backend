<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 2/10/2020
 * Time: 10:29 PM
 */

namespace Okotieno\SchoolExams\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolExams\Models\ExamPaper;
use Okotieno\SchoolExams\Models\ExamPaperQuestion;
use Okotieno\SchoolExams\Models\ExamPaperQuestionTag;

class ExamPaperQuestionsController extends Controller
{
  /**
   * @param Request $request
   * @param ExamPaper $examPaper
   * @return JsonResponse
   */
  public function index(Request $request, ExamPaper $examPaper)
  {
    $response = [];

    return response()->json(
      $response
    );
  }

  /**
   * @param ExamPaper $examPaper
   * @return JsonResponse
   */

  public function show(ExamPaper $examPaper, ExamPaperQuestion $examPaperQuestion)
  {

    return response()->json($examPaperQuestion);
  }

  public function store(Request $request, ExamPaper $examPaper)
  {
    foreach ($request->all() as $req) {
      if (($newPaperQuestion = $examPaper->questions()->find($req['id'])) == null) {
        $newPaperQuestion = $examPaper->questions()->create([
          'description' => $req['description'],
          'correct_answer_description' => $req['correctAnswerDescription'],
          'multiple_answers' => $req['multipleAnswers'],
          'multiple_choices' => $req['multipleChoices'],
          'points' => $req['points']
        ]);
      } else {
        $newPaperQuestion->update([
          'description' => $req['description'],
          'correct_answer_description' => $req['correctAnswerDescription'],
          'multiple_answers' => $req['multipleAnswers'],
          'multiple_choices' => $req['multipleChoices'],
          'points' => $req['points']
        ]);
      }
      foreach ($req['answers'] as $answer) {
        if (key_exists('id', $answer) && ($newPaperQuestion->answers()->find($answer['id']) != null)) {
          $newPaperQuestion->answers()->find($answer['id'])->update([
            'description' => $answer['description'],
            'is_correct' => $answer['isCorrect'],
          ]);
        } else {
          $newPaperQuestion->answers()->create([
            'description' => $answer['description'],
            'is_correct' => $answer['isCorrect'],
          ]);
        }
      }
      $newPaperQuestion->tags()->detach();
      foreach ($req['tags'] as $tag) {
        $tag = ExamPaperQuestionTag::firstOrNew(['name' => $tag]);
        $newPaperQuestion->tags()->save($tag);
      }
    }
    $newPaperQuestion->tags;
    $newPaperQuestion->answers;
    return response()->json([
      'saved' => true,
      'message' => 'Question Successfully saved',
      'data' => $newPaperQuestion
    ])->setStatusCode(201);
  }

  /**
   * @param ExamPaperQuestion $examPaperQuestion
   * @return array
   */
  public function destroy(ExamPaperQuestion $examPaperQuestion)
  {
    $examPaperQuestion->delete();
    return [
      'saved' => true,
      'message' => 'Exam Paper Question Successfully deleted'
    ];
  }
}
