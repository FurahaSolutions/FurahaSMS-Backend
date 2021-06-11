<?php


namespace Okotieno\SchoolStreams\Requests;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateStreamRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('create class stream');
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
      'abbreviation' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'Name field is required',
      'abbreviation.required' => 'Abbreviation is required',

    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create a stream'
    );
  }
}
