<?php

namespace Okotieno\SchoolCurriculum\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateClassLevelRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('create class level');
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
      'class_level_category_id' => 'required',
      'abbreviation' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'The name field is required',
      'abbreviation.required' => 'The Abbreviation field required',
      'class_level_category_id.required' => 'The class level category field is required'
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create class level'
    );
  }
}
