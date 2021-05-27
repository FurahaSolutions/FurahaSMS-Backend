<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordEmailRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'email' => 'required|email:exists:user,email'
      //
    ];
  }
  public function messages()
  {
    return [
      'email.exists' => 'Unknown user',
      'email.email' => 'Please provide a valid email',

    ];
  }
}
