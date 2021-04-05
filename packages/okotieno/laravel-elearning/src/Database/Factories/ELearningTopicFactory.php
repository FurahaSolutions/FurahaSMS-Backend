<?php


namespace Okotieno\ELearning\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\ELearning\Models\ELearningCourse;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\ELearning\Models\TopicNumberStyle;

class ELearningTopicFactory extends Factory
{
  protected $model = ELearningTopic::class;

  public function definition()
  {
    $eLearningCourseId = ELearningCourse::factory()->create()->id;
    $numberStyleId = TopicNumberStyle::factory()->create()->id;
    return [
      'description' => $this->faker->sentence,
      'e_learning_course_id' => $eLearningCourseId,
      'topic_number_style_id' => $numberStyleId
    ];
  }
}
