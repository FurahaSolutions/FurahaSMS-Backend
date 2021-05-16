<?php


namespace Okotieno\Students\Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Students\Models\Student;

class StudentFactory extends Factory
{
  protected $model = Student::class;

  /**
   * @inheritDoc
   */
  public function definition()
  {
    return [
      'user_id' => User::factory()->create()->id,
      'student_school_id_number' => $this->faker->year . $this->faker->randomNumber(5)
    ];
  }
}
