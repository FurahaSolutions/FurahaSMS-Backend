<?php


namespace Okotieno\AcademicYear\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\ArchivableItem;
use Okotieno\AcademicYear\Requests\AcademicYearArchiveRequest;

class AcademicYearArchivesController extends Controller
{
  public function open(AcademicYearArchiveRequest $request, AcademicYear $academicYear, $openItem)
  {
    $academicYear->archivableItems()
      ->wherePivot('archivable_item_id', ArchivableItem::where('slug', $openItem)->first()->id)
      ->detach();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully closed ' . $openItem . ' for academic year ' . $academicYear->name
    ]);
  }

  public function close(AcademicYearArchiveRequest $request, AcademicYear $academicYear, $closeItem)
  {
    $academicYear->archivableItems()
      ->save(ArchivableItem::where('slug', $closeItem)->first());

    return response()->json([
      'saved' => true,
      'message' => 'Successfully closed ' . $closeItem . ' for academic year ' . $academicYear->name
    ]);
  }

  public function archive(AcademicYearArchiveRequest $request, AcademicYear $academicYear)
  {
    $academicYear->archived_at = new Carbon();
    $academicYear->save();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully archived ' . $academicYear->name
    ]);
  }

  public function unarchive(AcademicYear $academicYear)
  {
    if(!auth()->user()->can('unarchive academic year')) {
      abort(403, 'You are not authorised to unarchive an academic year');
    }
    $academicYear->archived_at = null;
    $academicYear->save();
    return response()->json([
      'saved' => true,
      'message' => 'Successfully unarchived ' . $academicYear->name
    ]);
  }

}
