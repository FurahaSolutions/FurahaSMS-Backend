<?php

namespace Okotieno\SchoolCurriculum\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUnitRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('delete unit');
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
      'You are not authorised to delete unit'
    );
  }
}
