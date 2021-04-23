<?php

namespace Okotieno\LMS\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLibraryBookRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('update library book');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'title' => 'required',
      'category' => 'required',
      'ISBN' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'title.required' => 'The book title is required',
      'category.required' => 'The book Category is required',
      'ISBN.required' => 'The ISBN Number Field is required'
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to update a library book'
    );
  }
}
