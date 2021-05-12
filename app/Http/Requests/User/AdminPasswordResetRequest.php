<?php

namespace App\Http\Requests\User;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class AdminPasswordResetRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {

    return auth()->user()->can('reset user password');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'reset_password' => 'required'
    ];
  }
  protected function failedAuthorization()
  {
    throw new AuthorizationException( 'You are not authorised to reset user password');
  }
}
