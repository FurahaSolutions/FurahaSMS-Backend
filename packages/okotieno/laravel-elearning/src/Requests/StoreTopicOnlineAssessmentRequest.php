<?php

namespace Okotieno\ELearning\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreTopicOnlineAssessmentRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('create online assessment');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'available_at' => 'required',
      'closing_at' => 'required',
      'period' => 'required',
      'name' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'The name of the assessment is required',
      'available_at.required' => 'The time the assessment can be accessed is required',
      'closing_at.required' => 'The time the assessment closes is required',
      'period.required' => 'The period for the assessment required',
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create an assessment'
    );
  }
}
