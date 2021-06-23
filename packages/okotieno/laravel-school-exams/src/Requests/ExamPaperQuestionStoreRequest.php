<?php

namespace Okotieno\SchoolExams\Requests;

use App\Rules\ValidateArrayElementRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ExamPaperQuestionStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('create exam paper question');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      '' => [new ValidateArrayElementRule() ],
      '*.description' => 'required'
    ];
  }

  public function messages()
  {
    return [
      '0.description' => 'required',
      '*.description.required' => 'Each question must have a description'
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create exam paper question'
    );
  }
}
