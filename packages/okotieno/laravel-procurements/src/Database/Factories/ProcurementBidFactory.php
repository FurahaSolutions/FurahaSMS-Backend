<?php


namespace Okotieno\Procurement\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Procurement\Models\ProcurementBid;
use Okotieno\Procurement\Models\ProcurementTender;
use Okotieno\Procurement\Models\ProcurementVendor;

class ProcurementBidFactory extends Factory
{
  protected $model = ProcurementBid::class;
  public function definition()
  {
    return [
      'price_per_unit' => $this->faker->numberBetween(50, 100),
      'description' => $this->faker->sentence,
      'unit_description' => $this->faker->sentence,
      'vendor_id' => ProcurementVendor::factory()->create()->id,
      'tender_id' => ProcurementTender::factory()->create()->id,
    ];
  }
}
