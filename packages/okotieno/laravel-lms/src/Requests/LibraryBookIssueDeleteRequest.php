<?php

namespace Okotieno\LMS\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class LibraryBookIssueDeleteRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('mark library book returned');
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
      'You are not authorised to mark a library book as returned'
    );
  }
}
