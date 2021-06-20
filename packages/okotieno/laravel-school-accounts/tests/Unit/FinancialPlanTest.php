<?php


namespace Okotieno\SchoolAccounts\Tests\Unit;


use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\AcademicYearUnitAllocation;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolAccounts\Models\FinancialCost;
use Okotieno\SchoolAccounts\Models\FinancialCostItem;
use Okotieno\SchoolAccounts\Models\TuitionFeeFinancialPlan;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\Semester;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Tests\TestCase;

class FinancialPlanTest extends TestCase
{

  /**
   * POST /api/accounts/academic-year/:academic_year/financial-plan
   * @group accounts
   * @group financial_plan
   * @test
   */
  public function unauthenticated_users_cannot_create_financial_plan()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->postJson('/api/accounts/academic-year/' . $academicYear->id . '/financial-plan')
      ->assertUnauthorized();
  }

  /**
   * POST /api/accounts/academic-year/:academic_year/financial-plan
   * @group accounts
   * @group financial_plan
   * @test
   */
  public function unauthorized_users_cannot_create_financial_plan()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/accounts/academic-year/' . $academicYear->id . '/financial-plan')
      ->assertForbidden();
  }

  /**
   * POST /api/accounts/academic-year/:academic_year/financial-plan
   * @group accounts
   * @group financial_plan
   * @test
   */
  public function authorized_users_can_create_financial_plan()
  {
    $semesters = Semester::factory()->count(2)->create()->toArray();
    $classLevels = ClassLevel::factory()->count(2)->create()->toArray();
    $data = [
      'tuitionFee' => array_map(function ($classLevel) use ($semesters) {
        return [
          'classLevelId' => $classLevel['id'],
          'unitLevels' => array_map(function ($unitLevel) use ($semesters) {
            return [
              'id' => $unitLevel['id'],
              'semesters' => array_map(function ($sem) {
                return ['id' => $sem['id'], 'amount' => $this->faker->numberBetween(1000, 2000)];
              }, $semesters)
            ];
          }, UnitLevel::factory()->count(2)->create()->toArray())
        ];
      }, $classLevels),
      'otherFees' => array_map(function ($classLevel) use ($semesters) {
        return [
          'classLevelId' => $classLevel['id'],
          'financialCosts' => array_map(function ($financialCost) use ($semesters) {
            return [
              'id' => $financialCost['id'],
              'costItems' => array_map(function ($financialCostItem) use ($semesters) {
                return [
                  'id' => $financialCostItem['id'],
                  'semesters' => array_map(function ($sem) {
                    return ['id' => $sem['id'], 'amount' => $this->faker->numberBetween(1000, 2000)];
                  }, $semesters)
                ];
              }, FinancialCostItem::factory()->state(['financial_cost_id' => $financialCost['id']])->count(2)->create()->toArray())
            ];

          }, FinancialCost::factory()->count(2)->create()->toArray())
        ];
      }, $classLevels)
    ];

    Permission::factory()->state(['name' => 'create financial plan'])->create();
    $this->user->givePermissionTo('create financial plan');
    $academicYear = AcademicYear::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('/api/accounts/academic-year/' . $academicYear->id . '/financial-plan', $data)
      ->assertOk();
  }

  /**
   * GET /api/accounts/academic-year/:academic_year/financial-plan
   * @group accounts
   * @group financial_plan
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_financial_plan()
  {
    $academicYear = AcademicYear::factory()->create();
    $this->getJson('/api/accounts/academic-year/' . $academicYear->id . '/financial-plan')
      ->assertUnauthorized();
  }

  /**
   * GET /api/accounts/academic-year/:academic_year/financial-plan
   * @group accounts
   * @group financial_plan
   * @test
   */
  public function authenticated_users_can_retrieve_financial_plan()
  {
    $academicYear = AcademicYear::factory()->create();
    TuitionFeeFinancialPlan::factory()->count(3)->state(['academic_year_id' => $academicYear->id])->create();
    $allocations = AcademicYearUnitAllocation::factory()->count(2)->state(['academic_year_id' => $academicYear->id])->create();
    FinancialCostItem::factory()->count(2)->create();

    foreach ($allocations->pluck('class_level_id')->toArray() as $classLevelId) {
      foreach (ClassLevel::find($classLevelId)->unitLevels as $unitLevel) {
        $unitLevel->semesters()->save(Semester::factory()->create());
        $unitLevel->semesters()->save(Semester::factory()->create());
      }
    }
    $this->actingAs($this->user, 'api')->getJson('/api/accounts/academic-year/' . $academicYear->id . '/financial-plan')
      ->assertOk();
  }

}
