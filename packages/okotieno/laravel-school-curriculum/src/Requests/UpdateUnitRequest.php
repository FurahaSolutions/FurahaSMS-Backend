<?php

namespace Okotieno\SchoolCurriculum\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update unit');
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
            'unit_category_id' => 'required',
            'abbreviation' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required'=> 'The Unit/Subject is required',
            'unit_category_id.required' => 'The unit category field is required'
        ];
    }
  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create unit'
    );
  }
}
