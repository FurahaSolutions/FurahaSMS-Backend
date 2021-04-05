<?php


namespace Okotieno\SchoolExams\Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Okotieno\SchoolExams\Models\ExamPaper;

class ExamPaperFactory extends Factory
{
  protected $model = ExamPaper::class;

  public function definition()
  {
    return [
      'name' => $this->faker->sentence,
      'created_by' => User::factory()->create()->id,
      'unit_level_id' => UnitLevel::factory()->create()->id
    ];
  }
}
