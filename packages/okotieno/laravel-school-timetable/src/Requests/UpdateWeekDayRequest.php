<?php

namespace Okotieno\TimeTable\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWeekDayRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('update weekday');
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
      'abbreviation' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'week day name is required',
      'abbreviation.required' => 'Week day abbreviation is required',
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to update a week day'
    );
  }
}
