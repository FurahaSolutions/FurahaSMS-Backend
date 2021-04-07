<?php

namespace Okotieno\ELearning\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreELearningCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create e-learning course');
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
          'description' => 'required',
          'academic_year_id' => 'required',
          'class_level_id' => 'required',
          'unit_level_id' => 'required',
          'unit_id' => 'required',
          'numbering' => 'required',
          'topics' => 'present|array',
        ];
    }
    public function messages()
    {
        return [
          'name.required' => 'Name field is required',
          'description.required' => 'Description field is required',
          'academic_year_id.required' => 'Academic year field is required',
          'class_level_id.required' => 'Class Level is require',
          'unit_level_id.required' => 'Unit Level is required',
          'unit_id.required' => 'Unit is required',
          'numbering.required' => 'Number style is required',
          'topics.required' => 'Topics are required',
          'topics.array' => 'Topics must be an array of items'
        ];
    }
    protected function failedAuthorization()
    {
        throw new AuthorizationException(
            'You are not authorised to Create a an E - Learning Course'
        );
    }
}
