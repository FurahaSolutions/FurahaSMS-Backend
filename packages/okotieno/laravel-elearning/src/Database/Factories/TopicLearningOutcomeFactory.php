<?php


namespace Okotieno\ELearning\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\ELearning\Models\ELearningCourse;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\ELearning\Models\TopicLearningOutcome;
use Okotieno\ELearning\Models\TopicNumberStyle;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\UnitLevel;

class TopicLearningOutcomeFactory extends Factory
{
  protected $model = TopicLearningOutcome::class;

  public function definition()
  {
    return [
      'description' => $this->faker->sentence,
      'e_learning_topic_id' => ELearningTopic::factory()->create()->id,
    ];
  }
}
