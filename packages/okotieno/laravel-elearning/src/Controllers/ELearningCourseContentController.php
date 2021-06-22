<?php


namespace Okotieno\ELearning\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Okotieno\ELearning\Models\ELearningCourseContent;
use Okotieno\ELearning\Requests\ELearningCourseContentDeleteRequest;
use Okotieno\ELearning\Requests\StoreELearningCourseContentRequest;
use Okotieno\ELearning\Requests\ELearningCourseContentUpdateRequest;
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
   * @param ELearningCourseContentDeleteRequest $request
   * @param ELearningCourseContent $course_content
   * @return JsonResponse
   */
  public function destroy(ELearningCourseContentDeleteRequest $request, ELearningCourseContent $course_content): JsonResponse
  {

    $course_content->deleteStudyMaterial($request->all());
    return response()->json([
      'saved' => true,
      'message' => 'Successfully deleted Course Content',
    ]);
  }

  /**
   * @param ELearningCourseContentUpdateRequest $request
   * @param ELearningCourseContent $course_content
   * @return array|JsonResponse
   */
  public function update(ELearningCourseContentUpdateRequest $request, ELearningCourseContent $course_content)
  {

    $studyMaterial = StudyMaterial::find($course_content->study_material_id);
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
