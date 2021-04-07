<?php


namespace Okotieno\ELearning\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\ELearning\Models\ELearningCourseContent;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\StudyMaterials\Models\StudyMaterial;

class ELearningCourseContentFactory extends Factory
{
  protected $model = ELearningCourseContent::class;

  public function definition()
  {
    return [
      'study_material_id' => StudyMaterial::factory()->create()->id,
      'e_learning_topic_id' => ELearningTopic::factory()->create()->id
    ];

  }
}
