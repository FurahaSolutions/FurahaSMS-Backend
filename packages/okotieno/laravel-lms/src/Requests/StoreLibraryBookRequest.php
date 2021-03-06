<?php

namespace Okotieno\LMS\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryBookRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('create library book');
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
      'tags' => 'required',
      'ISBN' => 'required',
      'authors' => 'required',
      'publishers' => 'required',
      'book_items.*.ref' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'title.required' => 'The book title is required',
      'category.required' => 'The book Category is required',
      'ISBN.required' => 'The ISBN Number Field is required',
      'authors.required' => 'At least one author must be provided',
      'tags.required' => 'At least one tag must be provided',
      'publishers.required' => 'At least one publisher must be provided',
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to create a library book'
    );
  }
}
