<?php


namespace Okotieno\PermissionsAndRoles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\PermissionsAndRoles\Models\Role;

class RoleFactory extends Factory
{
  protected $model = Role::class;

  public function definition()
  {
    return [
      'name' => $this->faker->realText(20),
      'is_staff' => $this->faker->boolean
    ];
  }

  public function staff()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_staff' => true,
      ];
    });
  }
  public function nonStaff()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_staff' => false,
      ];
    });
  }
}
