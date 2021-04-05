<?php


namespace Okotieno\ELearning\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\ELearning\Models\ELearningCourse;
use Okotieno\ELearning\Models\TopicNumberStyle;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\UnitLevel;

class ELearningCourseFactory extends Factory
{
  protected $model = ELearningCourse::class;

  public function definition()
  {
    $academicYearId = AcademicYear::factory()->create()->id;
    $numberStyleId = TopicNumberStyle::factory()->create()->id;
    $classLevelId = ClassLevel::factory()->create()->id;
    $unitLevel = UnitLevel::factory()->create();
    return [
      'name' => $this->faker->sentence,
      'description' => $this->faker->sentence,
      'academic_year_id' => $academicYearId,
      'class_level_id' => $classLevelId,
      'unit_level_id' => $unitLevel->id,
      'unit_id' => $unitLevel->unit_id,
      'topic_number_style_id' => $numberStyleId,
    ];
  }
}
