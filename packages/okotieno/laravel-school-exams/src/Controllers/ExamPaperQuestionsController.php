<?php


namespace Okotieno\SchoolExams\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolExams\Models\ExamPaper;
use Okotieno\SchoolExams\Models\ExamPaperQuestion;
use Okotieno\SchoolExams\Models\ExamPaperQuestionTag;
use Okotieno\SchoolExams\Requests\ExamPaperQuestionStoreRequest;
use Okotieno\SchoolExams\Requests\ExamPaperQuestionDeleteRequest;

class ExamPaperQuestionsController extends Controller
{

  /**
   * @param ExamPaper $examPaper
   * @param ExamPaperQuestion $examPaperQuestion
   * @return JsonResponse
   */

  public function show(ExamPaper $examPaper,  ExamPaperQuestion $question)
  {
    return response()->json($question);
  }

  public function store(ExamPaperQuestionStoreRequest $request, ExamPaper $examPaper)
  {
    foreach ($request->questions as $req) {
      $id = key_exists('id', $req) ? $req['id'] : null;
      $newPaperQuestion = $examPaper->questions()->find($id);
      if ($newPaperQuestion == null) {
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
      $newPaperQuestion->tags;
      $newPaperQuestion->answers;
    }

    return response()->json([
      'saved' => true,
      'message' => 'Question Successfully saved',
      'data' => $examPaper
    ])->setStatusCode(201);
  }

  /**
   * @param ExamPaperQuestion $examPaperQuestion
   * @param ExamPaperQuestionDeleteRequest $request
   * @return array
   */
  public function destroy(ExamPaperQuestion $examPaperQuestion, ExamPaperQuestionDeleteRequest $request)
  {
    $examPaperQuestion->delete();
    return [
      'saved' => true,
      'message' => 'Exam Paper Question Successfully deleted'
    ];
  }
}
