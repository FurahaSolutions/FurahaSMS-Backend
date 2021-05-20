<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 12/12/2019
 * Time: 11:28 AM
 */

namespace Okotieno\Files\Controllers;


use App\Http\Controllers\Controller;
use Okotieno\Files\Models\FileDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
  public function index(Request $request)
  {
    $userId = $request->userId;
    $profilePicture = $request->profilePicture;
    if ($profilePicture && ($userId !== null)) {
      $picture = User::find($userId)->profilePics->last();
      if ($picture != null) {
        return Storage::download($picture->fileDocument->file_path);
      }
    }
  }

  public function store(Request $request)
  {

    if ($request->profilePicture !== null) {
      if(!auth()->user()->can('upload profile picture')) {
        abort(403, 'You are not authorised to upload profile picture');
      }
      $filePath = Storage::put('uploads/profile_picture', $request->profilePicture);
      $saved_content = User::find(auth()->id())->uploadFileDocument()->create([
        'name' => $request->profilePicture->getClientOriginalName(),
        'type' => $request->profilePicture->getClientOriginalExtension(),
        'extension' => $request->profilePicture->getClientOriginalExtension(),
        'mme_type' => $request->profilePicture->getMimeType(),
        'size' => $request->profilePicture->getSize(),
        'file_path' => $filePath
      ]);
      return response([
        'saved' => true,
        'message' => 'The Upload was successful',
        'data' => $saved_content
      ])->setStatusCode(201);
    }
  }

  public function show($fileDocumentId)
  {
    $fileDocument = FileDocument::find($fileDocumentId);
    if ($fileDocument != null) {
      return Storage::download($fileDocument->file_path);
    }
  }

}

