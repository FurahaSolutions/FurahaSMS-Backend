<?php

namespace Okotieno\AcademicYear\Database\Factories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\ArchivableItem;
use Okotieno\PermissionsAndRoles\Models\Permission;

class ArchivableItemFactory extends Factory
{
  protected $model = ArchivableItem::class;


  public function definition()
  {

    return [
      'name' => $this->faker->name,
      'slug' => $this->faker->slug,
      'permission_id' => Permission::factory()->create()->id,
      'reopen_permission_id' => Permission::factory()->create()->id,
    ];
  }
}
