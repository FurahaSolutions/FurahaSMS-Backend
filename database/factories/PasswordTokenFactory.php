<?php

namespace Database\Factories;

use App\Models\PasswordToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasswordTokenFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = PasswordToken::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'user_id' => User::factory()->create()->id,
      'token' => bcrypt($this->faker->password),
      'expires_at' => now()->addHour()
    ];
  }
}

