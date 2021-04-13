<?php


namespace Okotieno\Procurement\Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Procurement\Models\ProcurementItemsCategory;
use Okotieno\Procurement\Models\ProcurementRequest;

class ProcurementRequestFactory extends Factory
{
  protected $model = ProcurementRequest::class;
  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'description' => $this->faker->name,
      'procurement_items_category_id' => ProcurementItemsCategory::factory()->create()->id,
      'quantity_description' => $this->faker->sentence,
      'requested_by' => User::factory()->create()->id
    ];
  }
}
