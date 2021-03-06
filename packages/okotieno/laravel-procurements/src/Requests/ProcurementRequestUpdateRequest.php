<?php


namespace Okotieno\Procurement\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ProcurementRequestUpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {

    $requestedById = Route::current()->parameters()['procurement']->requested_by;

    return auth()->id() === $requestedById || auth()->user()->can('update procurement request');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'required',
      'procurement_items_category_id' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'Name of item to be procured is required',
      'procurement_items_category_id.required' => 'Item Category is required'
    ];
  }

  protected function failedAuthorization()
  {
    throw new \Illuminate\Auth\Access\AuthorizationException(
      'You are not authorised to update a procurement request'
    );
  }
}
