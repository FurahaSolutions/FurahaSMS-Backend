<?php

namespace Okotieno\Teachers\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class TeacherSubjectStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can("assign unit to teacher");
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'units'=> 'required',
      'units.*'=> 'required|exists:unit_levels,id',
    ];
  }

  public function messages()
  {
    return [
      'units.required' => 'Units are required',
      'units.*.required'=> 'Units are required',
      'units.*.exists'=> 'Please provide valid unit levels',
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to assign unit to teacher'
    );
  }
}
