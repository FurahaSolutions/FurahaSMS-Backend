<?php

namespace Okotieno\SchoolCurriculum\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateClassLevelUnitLevelRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('allocate unit levels to class levels');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'allocations' => 'required|array',
      'allocations.*.id' => 'required|exists:class_levels',
      'allocations.*.unitLevels' => 'required',
      'allocations.*.unitLevels.*' => 'required|exists:unit_levels,id',
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'The class allocations field is required',
      'name.array' => 'The class allocations field must be an array',
      'allocations.*.id' => 'Please provide id field in all allocations',
      'allocations.*.unitLevels' => 'Please provide unitLevels field in all allocations',
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to allocate unit levels to class level'
    );
  }
}
