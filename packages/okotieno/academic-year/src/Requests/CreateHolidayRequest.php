<?php

namespace Okotieno\AcademicYear\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed name
 * @property mixed start_date
 * @property mixed end_date
 * @property mixed class_levels
 */
class CreateHolidayRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return auth()->user()->can('create holiday');
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
      'occurs_on' => 'required|date_format:Y-m-d'
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'The name field is required',
      'occurs_on.required' => 'The name field is required',
    ];
  }

  protected function failedAuthorization()
  {
    throw new \Illuminate\Auth\Access\AuthorizationException(
      'You are not authorised to create an academic year holiday'
    );
  }
}
