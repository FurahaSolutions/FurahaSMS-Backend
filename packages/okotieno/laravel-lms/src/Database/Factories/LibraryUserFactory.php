<?php

namespace Okotieno\LMS\Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\LMS\Models\LibraryUser;

class LibraryUserFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = LibraryUser::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'user_id' => User::factory()->create()->id
    ];
  }

  public function suspended()
  {
    return $this->state(function (array $attributes) {
      return [
        'suspended' => true,
      ];
    });
  }
}
