<?php


namespace Okotieno\SchoolExams\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\SchoolExams\Models\ExamPaper;
use Okotieno\SchoolExams\Models\OnlineAssessment;

class OnlineAssessmentFactory extends Factory
{
  protected $model = OnlineAssessment::class;

  public function definition()
  {

    return [
      'available_at'=>$this->faker->dateTime->format('Y-m-d H:i:s.v'),
      'closing_at' => $this->faker->dateTime->format('Y-m-d H:i:s.v'),
      'period' => $this->faker->word,
      'exam_paper_id' => ExamPaper::factory()->create()->id,
      'e_learning_topic_id' => ELearningTopic::factory()->create()->id
    ];
  }
}
