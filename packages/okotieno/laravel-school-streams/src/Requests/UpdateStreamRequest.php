<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 1/10/2020
 * Time: 7:59 PM
 */

namespace Okotieno\SchoolStreams\Requests;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStreamRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('update class stream');
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
      'You are not authorised to update a stream'
    );
  }
}
