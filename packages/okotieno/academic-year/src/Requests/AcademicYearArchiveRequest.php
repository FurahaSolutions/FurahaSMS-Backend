<?php

namespace Okotieno\AcademicYear\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Okotieno\AcademicYear\Models\ArchivableItem;

/**
 * @property mixed name
 * @property mixed start_date
 * @property mixed end_date
 * @property mixed class_levels
 */
class AcademicYearArchiveRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    $closeItem = Route::current()->parameters()['closeItem'];

    $permission = ArchivableItem::where('slug', $closeItem)->first()->permission->name;

    return auth()->user()->can($permission);

  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [

    ];
  }

  public function messages()
  {
    return [

    ];
  }

  protected function failedAuthorization()
  {
    throw new \Illuminate\Auth\Access\AuthorizationException(
      'You are not authorised to create an academic year holiday'
    );
  }
}