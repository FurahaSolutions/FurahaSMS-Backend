<?php


namespace Okotieno\SchoolAccounts\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolAccounts\Models\FinancialCostItem;

class FinancialCostItemFactory extends Factory
{

  protected $model = FinancialCostItem::class;
  public function definition()
  {
    return [
      'name' => $this->faker->state,
      'active' => $this->faker->boolean,
    ];
  }
}
