<?php


namespace Okotieno\Procurement\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Procurement\Models\ProcurementVendor;

class ProcurementVendorFactory extends Factory
{
  protected $model = ProcurementVendor::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'description' => $this->faker->sentence,
      'physical_address' => $this->faker->address,
      'active' => $this->faker->boolean

    ];
  }
}
