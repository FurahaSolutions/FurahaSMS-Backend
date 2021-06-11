<?php


namespace Okotieno\SchoolExams\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolExams\Models\ExamPaper;
use Okotieno\SchoolExams\Models\ExamPaperQuestion;

class ExamPaperQuestionFactory extends Factory
{
  protected $model = ExamPaperQuestion::class;

  public function definition()
  {
    return [
      'description' => $this->faker->paragraph,
      'correct_answer_description' => $this->faker->paragraph,
      'multiple_answers' => $this->faker->boolean,
      'multiple_choices' => $this->faker->boolean,
      'exam_paper_id' => ExamPaper::factory()->create()->id,
      'points' => $this->faker->numberBetween(2, 10)
    ];
  }
}
