<?php

namespace Okotieno\AcademicYear\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class AcademicYearRestoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return auth()->user()->can('restore academic year');
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


  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to restore deleted academic year'
    );
  }
}
