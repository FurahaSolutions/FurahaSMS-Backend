<?php


namespace Okotieno\SchoolExams\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolExams\Models\ExamPaperQuestionTag;

class ExamPaperQuestionTagFactory extends Factory
{
  protected $model = ExamPaperQuestionTag::class;
  public function definition()
  {
    return [
      'name' => $this->faker->word
    ];
  }
}
