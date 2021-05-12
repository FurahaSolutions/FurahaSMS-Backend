<?php


namespace Okotieno\Guardians\Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Guardians\Models\Guardian;

class GuardianFactory extends Factory
{
  protected $model = Guardian::class;

  public function definition()
  {
    return [
      'user_id' => User::factory()->create()->id
    ];
  }
}
