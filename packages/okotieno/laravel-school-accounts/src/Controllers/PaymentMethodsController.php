<?php

namespace Okotieno\SchoolAccounts\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okotieno\SchoolAccounts\Models\PaymentMethod;

class PaymentMethodsController extends Controller
{

    public function index(Request $request)
    {
        if($request->active == null) {

            return PaymentMethod::active()->get();
        }
        return PaymentMethod::all();
    }

}
