<?php


namespace Okotieno\ELearning\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\ELearning\Models\ELearningCourseContent;
use Okotieno\ELearning\Requests\StoreELearningCourseContentRequest;
use Okotieno\StudyMaterials\Models\StudyMaterial;

class ELearningCourseContentController extends Controller
{
  public function store(StoreELearningCourseContentRequest $request)
  {

    ELearningCourseContent::saveStudyMaterial($request);
    return response()->json([
      'saved' => true,
      'message' => 'Successfully saved Course Contents',
      'data' => []
    ])->setStatusCode(201);
  }

  /**
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(Request $request, $eLearningCourseContentId)
  {

    $eLearningCourseContent = ELearningCourseContent::find($eLearningCourseContentId);
    $eLearningCourseContent->deleteStudyMaterial($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted Course Content',
      'data' => []
    ]);
  }

  /**
   * @param Request $request
   * @param $eLearningCourseContentId
   * @return array|\Illuminate\Http\JsonResponse
   */
  public function update(Request $request, $eLearningCourseContentId)
  {
    $eLearningCourseContent = ELearningCourseContent::find($eLearningCourseContentId);
    $studyMaterial = StudyMaterial::find($eLearningCourseContent->study_material_id);
    $studyMaterial->update([
      'title' => $request->title
    ]);
    return response()->json([
      'saved' => true,
      'message' => 'Successfully updated Course Content',
      'data' => []
    ]);
  }
}
