<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProfilePic;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Okotieno\LMS\Models\LibraryBookPublisher;
use Okotieno\LMS\Requests\DeleteLibraryBookPublisherRequest;
use Okotieno\LMS\Requests\StoreLibraryBookPublisherRequest;
use Okotieno\LMS\Requests\UpdateLibraryBookPublisherRequest;

class LibraryBookPublisherController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    if ($request->publisher_id != null) {
      return response()->json(LibraryBookPublisher::find($request->publisher_id));
    }
    if ($request->name != null) {
      return response()->json(LibraryBookPublisher::where('name', 'LIKE', '%' . $request->name . '%')->get());

    }
    return response()->json(LibraryBookPublisher::all());
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param StoreLibraryBookPublisherRequest $request
   * @return JsonResponse
   */
  public function store(StoreLibraryBookPublisherRequest $request)
  {
    $saved_picture = null;
    if ($request->profilePicture !== null) {
      $filePath = Storage::put('uploads/library_publishers', $request->profilePicture);
      $saved_picture = User::find(auth()->id())->uploadFileDocument()->create([
        'name' => $request->profilePicture->getClientOriginalName(),
        'type' => $request->profilePicture->getClientOriginalExtension(),
        'extension' => $request->profilePicture->getClientOriginalExtension(),
        'mme_type' => $request->profilePicture->getMimeType(),
        'size' => $request->profilePicture->getSize(),
        'file_path' => $filePath
      ]);

    }
    $createdPublisher = LibraryBookPublisher::create([
      'name' => $request->name,
      'biography' => $request->biography
    ]);


    if ($saved_picture != null) {
      $profilePic = ProfilePic::create([
        'file_document_id' => $saved_picture->id,
        'user_id' => $createdPublisher->id
      ]);
      $createdPublisher->profilePics()->save($profilePic);
    }
    return response()->json([
      'saved' => true,
      'message' => 'Publisher saved Successfully',
      'data' => $createdPublisher
    ])->setStatusCode(201);
  }

  /**
   * Display the specified resource.
   *
   * @param LibraryBookPublisher $libraryBookPublisher
   * @return JsonResponse
   */
  public function show(LibraryBookPublisher $libraryBookPublisher)
  {
    return response()->json($libraryBookPublisher);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param LibraryBookPublisher $libraryBookPublisher
   * @return JsonResponse
   */
  public function update(UpdateLibraryBookPublisherRequest $request, LibraryBookPublisher $libraryBookPublisher)
  {

    $libraryBookPublisher->update(['name' => $request->name, 'biography' => $request->biography]);
    return response()->json([
      'saved' => true,
      'message' => 'Publisher Updated Successfully',
      'data' => LibraryBookPublisher::find($libraryBookPublisher->id)
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param LibraryBookPublisher $libraryBookPublisher
   * @return JsonResponse
   * @throws Exception
   */
  public function destroy(DeleteLibraryBookPublisherRequest $request, LibraryBookPublisher $libraryBookPublisher)
  {
    $libraryBookPublisher->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Publisher Updated Successfully',
    ]);
  }
}
