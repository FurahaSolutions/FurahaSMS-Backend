<?php


namespace Okotieno\Procurement\Database\Factories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Procurement\Models\ProcurementRequest;
use Okotieno\Procurement\Models\ProcurementTender;

class ProcurementTenderFactory extends Factory
{
  protected $model = ProcurementTender::class;
  public function definition()
  {
    return [
      'procurement_request_id' => ProcurementRequest::factory()->create()->id,
      'expiry_datetime' => new Carbon(),
      'description' => $this->faker->sentence
    ];
  }
}
