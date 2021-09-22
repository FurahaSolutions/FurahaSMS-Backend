<?php


namespace Okotieno\SchoolExams\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolExams\Models\ExamInstruction;

class ExamInstructionFactory extends Factory
{
  protected $model = ExamInstruction::class;

  public function definition()
  {
    return [
      'description' => $this->faker->paragraph,
      'position' => $this->faker->numberBetween(1, 9)
    ];
  }
}
