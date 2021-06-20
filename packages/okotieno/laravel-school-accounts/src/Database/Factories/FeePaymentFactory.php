<?php


namespace Okotieno\SchoolAccounts\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\SchoolAccounts\Models\FeePayment;
use Okotieno\SchoolAccounts\Models\PaymentMethod;
use Okotieno\Students\Models\Student;

class FeePaymentFactory extends Factory
{
  protected $model = FeePayment::class;
  public function definition()
  {
    return [
      'student_id' => Student::factory()->create()->id,
      'amount' => $this->faker->numberBetween(1000,30000),
      'payment_method_id' => PaymentMethod::factory()->create()->id,
      'ref' => $this->faker->colorName,
      'transaction_date' => $this->faker->date()
    ];
  }
}
