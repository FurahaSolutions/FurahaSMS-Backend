<?php

namespace Okotieno\SchoolCurriculum\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateClassLevelCategoryRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('create class level category');
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
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'The name field is required',
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create class level category'
    );
  }
}
