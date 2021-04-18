<?php

namespace Okotieno\SchoolCurriculum\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClassLevelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update class level');
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
            'name.required'=> 'The name field is required',
            'abbr.required' => 'The Abbreviation field required',
            'class_level_category_id' => 'The class level category field is required'
        ];
    }
  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to update class level'
    );
  }
}
