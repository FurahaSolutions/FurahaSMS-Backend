<?php

namespace Okotieno\AcademicYear\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class DeleteAcademicYearRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return auth()->user()->can('delete academic year');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [];
  }

  public function messages()
  {
    return [];
  }

  protected function failedAuthorization()
  {
    throw new \Illuminate\Auth\Access\AuthorizationException(
      'You are not authorised to delete an academic year'
    );
  }
}