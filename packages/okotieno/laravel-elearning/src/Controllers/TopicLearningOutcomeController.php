<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 12/12/2019
 * Time: 11:28 AM
 */

namespace Okotieno\ELearning\Controllers;


use App\Http\Controllers\Controller;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\ELearning\Requests\DeleteLearningOutcomeRequest;
use Okotieno\ELearning\Requests\StoreLearningOutcomeRequest;
use Okotieno\ELearning\Requests\UpdateLearningOutcomeRequest;


class TopicLearningOutcomeController extends Controller
{
  public function store(StoreLearningOutcomeRequest $request, ELearningTopic $eLearningTopic)
  {
    return response()->json([
      'saved' => true,
      'message' => 'Successfully saved Learning Outcome',
      'data' => $eLearningTopic->saveLearningOutcome($request)
    ])->setStatusCode(201);
  }

  public function update(UpdateLearningOutcomeRequest $request, ELearningTopic $eLearningTopic, $id)
  {
    $topicLearningOutcome = $eLearningTopic->learningOutcomes()->find($id);
    if ($topicLearningOutcome === null) {
      abort(404, 'Resource not found to topic');
    }
    $topicLearningOutcome->update([
      'description' => $request->description
    ]);

    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated Learning Outcome',
      'data' => $topicLearningOutcome
    ]);
  }

  public function destroy(DeleteLearningOutcomeRequest $request, ELearningTopic $eLearningTopic, $id)
  {
    $topicLearningOutcome = $eLearningTopic->learningOutcomes()->find($id);
    if ($topicLearningOutcome === null) {
      abort(404, 'Resource not found to topic');
    }
    $topicLearningOutcome->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted Learning Outcome'
    ]);
  }
}
