<?php


namespace Okotieno\Students\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\AcademicYearUnitAllocation;
use Okotieno\SchoolAccounts\Models\FinancialCostItem;
use Okotieno\SchoolAccounts\Models\TuitionFeeFinancialPlan;
use Okotieno\SchoolCurriculum\Models\ClassLevel;
use Okotieno\SchoolCurriculum\Models\Semester;

class StudentFeeStatementController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @param User $user
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(User $user)
  {
    $feeStructureTemp = [];
    $student = $user->student;
    $studentUnitAllocation = $user->student->unitAllocation;
    foreach ($studentUnitAllocation->groupBy(['academic_year_id', 'class_level_id']) as $unitAllocations) {
      foreach ($unitAllocations as $unitAllocation) {
        foreach ($unitAllocation as $unitAllocationItem) {
          // return $unitAllocationItem;
          foreach (TuitionFeeFinancialPlan::where([
            'unit_level_id' => $unitAllocationItem['unit_level_id'],
            'academic_year_id' => $unitAllocationItem['academic_year_id']
          ])->get() as $plan) {
            $feeStructureTemp[] = [
              'academic_year_id' => $plan['academic_year_id'],
              'academic_year_name' => AcademicYear::find($plan['academic_year_id'])->name,
              'amount' => $plan['amount'],
              'class_level_id' => $plan['class_level_id'],
              'class_level_name' => ClassLevel::find($plan['class_level_id'])->name,
              'semester_id' => $plan['semester_id'],
              'semester_name' => Semester::find($plan['semester_id'])->name,
              'unit_level_id' => $plan['unit_level_id'],

            ];
          }

        }

      }
    }

    $otherFees = [];
    foreach ($studentUnitAllocation->pluck('academic_year_id')->unique() as $item) {
      foreach (AcademicYear::find($item)->otherFeesFinancialPlan as $value) {
        $otherFees[] = [
          'academicYearId' => $item,
          'classLevelId' => $value['class_level_id'],
          'financialCostItemId' => $value['financial_cost_item_id'],
          'financialCostItemName' => FinancialCostItem::find($value['financial_cost_item_id'])->name,
          'amount' => $value['amount'],
          'semesterId' => $value['semester_id'],
        ];
      }
    }
    return response()->json([
      'feeStructure' => $feeStructureTemp,
      'otherFees' => $otherFees,
      'payments' => $student->feePayments
    ]);
  }


  /**
   * Store a newly created resource in storage.
   * @param Request $request
   * @param User $user
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request, User $user)
  {

    foreach ($request->all() as $allocation) {

      $user
        ->student
        ->unitAllocation()
        ->save(AcademicYearUnitAllocation::find($allocation));
    }

    return response()->json([
      'saves' => true,
      'message' => 'Successfully allocated units to the student',
      'data' => $user->student->unitAllocation
    ]);
  }

}
