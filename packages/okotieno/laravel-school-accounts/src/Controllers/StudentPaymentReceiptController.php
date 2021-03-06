<?php


namespace Okotieno\SchoolAccounts\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Okotieno\SchoolAccounts\Requests\StoreFeePaymentRequest;

class StudentPaymentReceiptController extends Controller
{

  public function store(StoreFeePaymentRequest $request, User $user)
  {

    $receipt = $user->student->feePayments()->create([
      'amount' => filter_var($request->amount, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND),
      'ref' => $request->ref,
      'payment_method_id' => $request->payment_method_id,
      'transaction_date' => $request->transaction_date
    ]);
    return [
      'saved' => true,
      'message' => 'Payment Successfully saved',
      'data' => $receipt
    ];
  }
}
