<?php

namespace Okotieno\AcademicYear\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed name
 * @property mixed start_date
 * @property mixed end_date
 * @property mixed class_levels
 */
class UpdateHolidayRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return auth()->user()->can('update holiday');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules(): array
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
    throw new AuthorizationException(
      'You are not authorised to update a holiday'
    );
  }
}
