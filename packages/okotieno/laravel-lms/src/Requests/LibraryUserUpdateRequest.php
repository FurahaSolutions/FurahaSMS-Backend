<?php

namespace Okotieno\LMS\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LibraryUserUpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    if (request()->get('blocked') == true && auth()->user()->can('block library user')) {
      return true;
    }
    if (request()->get('blocked') == false && auth()->user()->can('unblock library user')) {
      return true;
    }
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
    ];
  }


  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to update a library user'
    );
  }
}
