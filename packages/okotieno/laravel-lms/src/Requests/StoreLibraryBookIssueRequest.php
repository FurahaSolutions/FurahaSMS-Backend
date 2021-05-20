<?php

namespace Okotieno\LMS\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryBookIssueRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user()->can('issue library book');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'book_item_id' => 'required|exists:library_book_items,id',
      'user_id' => 'required|exists:users,id|exists:library_users,user_id',
    ];
  }

  public function messages()
  {
    return [
      'book_item_id.required' => 'Library Reference Number is Required',
      'user_id.required' => 'Library User is required',
      'user_id.exists:users' => 'Invalid User',
      'user_id.exists:library_users' => 'Invalid Library User',
    ];
  }

  protected function failedAuthorization()
  {
    throw new AuthorizationException(
      'You are not authorised to issue a library book'
    );
  }
}
