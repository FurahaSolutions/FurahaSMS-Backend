<?php


namespace Okotieno\SchoolAccounts\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolAccounts\Models\PaymentMethod;

class PaymentMethodFactory extends Factory
{
  protected $model = PaymentMethod::class;

  public function definition()
  {
     return [
       'name' => $this->faker->sentence(2),
       'active' => $this->faker->boolean(70)
     ];
  }
}
