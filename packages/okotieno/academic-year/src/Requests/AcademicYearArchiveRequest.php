<?php

namespace Okotieno\AcademicYear\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
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
    $routeParams = Route::current()->parameters();
    $closeItem = null;
    if (key_exists('closeItem', $routeParams)) {
      $closeItem = $routeParams['closeItem'];
      $permission = ArchivableItem::where('slug', $closeItem)->first()->permission->name;
      return auth()->user()->can($permission);
    }
    if (key_exists('openItem', $routeParams)) {
      $openItem = $routeParams['openItem'];
      $permission = Str::replaceFirst('close', 'open', ArchivableItem::where('slug', $openItem)->first()->permission->name);
      return auth()->user()->can($permission);
    }
    return auth()->user()->can('archive academic year');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [];
  }

  protected function failedAuthorization()
  {
    throw new \Illuminate\Auth\Access\AuthorizationException(
      'You are not authorised to create an academic year holiday'
    );
  }
}
