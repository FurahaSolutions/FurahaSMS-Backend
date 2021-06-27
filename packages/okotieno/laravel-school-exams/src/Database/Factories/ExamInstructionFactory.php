<?php


namespace Okotieno\SchoolExams\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolExams\Models\ExamInstruction;
use Okotieno\SchoolExams\Models\ExamPaper;

class ExamInstructionFactory extends Factory
{
  protected $model = ExamInstruction::class;

  public function definition()
  {
    return [
      'exam_paper_id' => ExamPaper::factory()->create()->id,
      'description' => $this->faker->sentence,
      'position' => $this->faker->numberBetween(1, 9)
    ];
  }
}
