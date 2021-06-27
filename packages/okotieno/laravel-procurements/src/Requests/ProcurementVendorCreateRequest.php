<?php


namespace Okotieno\Procurement\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ProcurementVendorCreateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('create procurement vendor');
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
      'procurement_items_categories' => 'required',
      'contactInfo.phones.*.name'=>'required',
      'contactInfo.phones.*.value'=>'required',
      'contactInfo.emails.*.value'=>'required',
      'contactInfo.emails.*.name'=>'required'
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'Name of item to be procured is required',
      'procurement_items_categories.required' => 'Item Category is required'
    ];
  }

  protected function failedAuthorization()
  {
    throw new \Illuminate\Auth\Access\AuthorizationException(
      'You are not authorised to make a procurement request'
    );
  }
}
