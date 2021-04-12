<?php


namespace Okotieno\Procurement\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Procurement\Models\ProcurementItemsCategory;

class ProcurementItemsCategoryFactory extends Factory
{
  protected $model = ProcurementItemsCategory::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name
    ];
  }
}
