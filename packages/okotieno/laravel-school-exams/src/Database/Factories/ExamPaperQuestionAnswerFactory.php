<?php


namespace Okotieno\SchoolExams\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolExams\Models\ExamPaperQuestion;
use Okotieno\SchoolExams\Models\ExamPaperQuestionAnswer;

class ExamPaperQuestionAnswerFactory extends Factory
{
  protected $model = ExamPaperQuestionAnswer::class;
  public function definition()
  {
    return [
      'description' => $this->faker->sentence,
      'is_correct' => $this->faker->boolean,
      'exam_paper_question_id' => ExamPaperQuestion::factory()->create()->id
    ];
  }
}
