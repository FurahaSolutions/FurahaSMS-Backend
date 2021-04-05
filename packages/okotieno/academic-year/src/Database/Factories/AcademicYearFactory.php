<?php

namespace Okotieno\AcademicYear\Database\Factories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;

class AcademicYearFactory extends Factory
{
  protected $model = AcademicYear::class;


  public function definition()
  {
    $startDate = Carbon::createFromFormat('Y', $this->faker->year);
    $endDate = Carbon::createFromFormat('Y',$startDate->year + 1);

    return [
      'name' => $startDate->year.'-'.rand(1000, 9999).'-'. $this->faker->randomLetter,
      'start_date' => $startDate->format('Y-m-d'),
      'end_date' => $endDate->format('Y-m-d'),
    ];
  }
}
