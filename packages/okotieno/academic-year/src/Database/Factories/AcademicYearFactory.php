<?php

namespace Okotieno\AcademicYear\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;

class AcademicYearFactory extends Factory
{
  protected $model = AcademicYear::class;


  public function definition()
  {

    return [
      'name' => $this->faker->year,
    ];
  }
}
