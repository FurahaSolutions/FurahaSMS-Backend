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
class CreateAcademicYearRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return auth()->user()->can('create academic year');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'required|unique:academic_years',
      'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date',
      'end_date' => 'required|date_format:Y-m-d',
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'The name field is required',
      'start_date.required' => 'The start date field required',
      'end_date.required' => 'The start date field required',
      'name.unique' => 'Academic year already exists',
      'start_date.date_format' => 'Incorrect date format provided',
      'start_date.before_or_equal' => 'Start date must be less than end date'
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create an academic year'
    );
  }
}
