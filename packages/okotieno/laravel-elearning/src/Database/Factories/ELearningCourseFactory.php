<?php


namespace Okotieno\ELearning\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\ELearning\Models\ELearningCourse;

class ELearningCourseFactory extends Factory
{
  protected $model = ELearningCourse::class;

  public function definition()
  {
    $academicYear = AcademicYear::factory()->create();
    return [
      'description' => $this->faker->sentence,
      'academic_year_id' => $academicYear->id
    ];
  }
}
