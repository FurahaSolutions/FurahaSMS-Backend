<?php

namespace Okotieno\ELearning\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\ELearning\Requests\StoreTopicOnlineAssessmentRequest;
use Okotieno\ELearning\Requests\TopicOnlineAssessmentUpdateRequest;
use Okotieno\SchoolExams\Models\OnlineAssessment;

class TopicOnlineAssessmentController extends Controller
{

  /**
   * @param OnlineAssessment $onlineAssessment
   * @param Request $request
   * @param null $topicId
   * @return JsonResponse
   * @throws AuthorizationException
   */
  public function show(OnlineAssessment $onlineAssessment, Request $request, $topicId = null)
  {
    if ($request->boolean("withQuestions") && Carbon::create($onlineAssessment->available_at) > Carbon::now()) {
      if(auth()->user()->cannot('update online assessment')) {
        throw new AuthorizationException('Assessment not yet available!');
      }
    }
    if ($request->boolean("withQuestions")) {
      $examPaper = $onlineAssessment->examPaper;
      return response()->json(array_merge([
        'questions' => $examPaper->questions
      ], $onlineAssessment->toArray()));
    }

    return response()->json($onlineAssessment);
  }

  public function store(ELearningTopic $eLearningTopic, StoreTopicOnlineAssessmentRequest $request)
  {
    return response()->json([
      'saved' => true,
      'message' => 'Successfully Created online Assessment',
      'data' => $eLearningTopic->saveOnlineAssessment($eLearningTopic, $request->all())
    ])->setStatusCode(201);
  }

  public function update(ELearningTopic $eLearningTopic = null, TopicOnlineAssessmentUpdateRequest $request, OnlineAssessment $online_assessment)
  {
    $online_assessment->update($request->all());
    $examPaper = $online_assessment->examPaper;
    $examPaper->name = $request->name;
    $examPaper->save();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully Created online Assessment',
      'data' => $online_assessment
    ]);
  }


  public function destroy(ELearningTopic $eLearningTopic, OnlineAssessment $onlineAssessment)
  {
    $eLearningTopic->onlineAssessments()->find($onlineAssessment->id)->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully Deleted online Assessment',
      'data' => []
    ]);
  }
}

