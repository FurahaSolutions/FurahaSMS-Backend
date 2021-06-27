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
      'questions' => ["required","array","min:1"],
      'questions.*.description' => 'required',
      'questions.*.correctAnswerDescription' => 'required',
      'questions.*.multipleAnswers' => 'required',
      'questions.*.multipleChoices' => 'required',
      'questions.*.points' => 'required',
      'questions.*.answers' => 'array',
      'questions.*.answers.*.description' => 'required',
      'questions.*.answers.*.isCorrect' => 'required',
      'questions.*.tags' => 'required',
    ];
  }

  public function messages()
  {
    return [
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
