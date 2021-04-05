<?php


namespace Okotieno\PermissionsAndRoles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\PermissionsAndRoles\Models\Permission;

class PermissionFactory extends Factory
{
  protected $model = Permission::class;

  public function definition()
  {
    return [
      'name' => $this->faker->realText(20)
    ];
  }
}
