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
      'name' => $this->faker->realText(20)
    ];
  }
}
