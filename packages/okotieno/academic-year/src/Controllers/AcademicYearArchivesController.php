<?php


namespace Okotieno\AcademicYear\Controllers;


use App\Http\Controllers\Controller;
use Okotieno\AcademicYear\Models\AcademicYear;
use Okotieno\AcademicYear\Models\ArchivableItem;
use Okotieno\AcademicYear\Requests\AcademicYearArchiveRequest;

class AcademicYearArchivesController extends Controller
{
  public function close(AcademicYearArchiveRequest $request, AcademicYear $academicYear, $closeItem)
  {
    $academicYear->archivableItems()
      ->save(ArchivableItem::where('slug', $closeItem)->first());

    return response()->json([
      'saved' => true,
      'message' => 'Successfully closed ' . $closeItem . ' for academic year ' . $academicYear->name
    ]);
  }

}
