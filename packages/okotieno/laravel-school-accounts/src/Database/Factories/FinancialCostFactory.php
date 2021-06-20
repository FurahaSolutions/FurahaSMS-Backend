<?php


namespace Okotieno\SchoolAccounts\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolAccounts\Models\FinancialCost;

class FinancialCostFactory extends Factory
{
  protected $model = FinancialCost::class;
  public function definition()
  {
    return [
      'name' => $this->faker->sentence(3),
      'active' => $this->faker->boolean
    ];
  }
}
