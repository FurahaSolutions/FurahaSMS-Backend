<?php


namespace Okotieno\SchoolAccounts\Tests\Unit;


use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\AcademicYearUnitAllocation;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolAccounts\Models\FeePayment;
use Okotieno\SchoolAccounts\Models\FinancialCost;
use Okotieno\SchoolAccounts\Models\FinancialCostItem;
use Okotieno\SchoolAccounts\Models\TuitionFeeFinancialPlan;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\Semester;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Okotieno\Students\Models\Student;
use Tests\TestCase;

class StudentPaymentReceiptTest extends TestCase
{

  /**
   * POST /api/students/:user/fee-payment-receipt
   * @group accounts
   * @group student_payment
   * @test
   */
  public function unauthenticated_users_cannot_receive_student_payment()
  {
    $student = Student::factory()->create();
    $this->postJson('/api/students/' . $student->user->id . '/fee-payment-receipt')
      ->assertUnauthorized();
  }

  /**
   * POST /api/students/:user/fee-payment-receipt
   * @group accounts
   * @group student_payment
   * @test
   */
  public function unauthorized_users_cannot_receive_student_payment()
  {
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/students/' . $student->user->id . '/fee-payment-receipt')
      ->assertForbidden();
  }

  /**
   * POST /api/students/:user/fee-payment-receipt
   * @group accounts
   * @group student_payment
   * @test
   */
  public function error_422_if_field_amount_missing()
  {
    Permission::factory()->state(['name'=>'receive student fee payment'])->create();
    $this->user->givePermissionTo('receive student fee payment');
    $student = Student::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/students/' . $student->user->id . '/fee-payment-receipt')
      ->assertStatus(422);
  }

  /**
   * POST /api/students/:user/fee-payment-receipt
   * @group accounts
   * @group student_payment
   * @test
   */
  public function authorized_users_can_receive_student_payment()
  {
    Permission::factory()->state(['name'=>'receive student fee payment'])->create();
    $this->user->givePermissionTo('receive student fee payment');
    $student = Student::factory()->create();
    $payment = FeePayment::factory()->create()->toArray();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/students/' . $student->user->id . '/fee-payment-receipt', $payment)
      ->assertOk();
  }

}
