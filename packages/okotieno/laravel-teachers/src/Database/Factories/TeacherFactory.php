<?php


namespace Okotieno\Teachers\Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Teachers\Models\Teacher;

class TeacherFactory extends Factory
{
  protected $model = Teacher::class;

  public function definition()
  {
     return [
       'user_id' => User::factory()->create()->id
     ];
  }
}
