<?php

namespace Okotieno\LMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Okotieno\Files\Models\ProfilePic;
use Okotieno\LMS\Models\LibraryBookPublisher;
use Okotieno\LMS\Requests\DeleteLibraryBookPublisherRequest;
use Okotieno\LMS\Requests\StoreLibraryBookPublisherRequest;
use Okotieno\LMS\Requests\UpdateLibraryBookPublisherRequest;

class LibraryBookPublisherController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
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
  public function store(StoreLibraryBookPublisherRequest $request): JsonResponse
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
   * @param LibraryBookPublisher $publisher
   * @return JsonResponse
   */
  public function show(LibraryBookPublisher $publisher)
  {
    return response()->json($publisher);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param UpdateLibraryBookPublisherRequest $request
   * @param LibraryBookPublisher $publisher
   * @return JsonResponse
   */
  public function update(UpdateLibraryBookPublisherRequest $request, LibraryBookPublisher $publisher): JsonResponse
  {

    $publisher->update(['name' => $request->name, 'biography' => $request->biography]);
    return response()->json([
      'saved' => true,
      'message' => 'Publisher Updated Successfully',
      'data' => LibraryBookPublisher::find($publisher->id)
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param DeleteLibraryBookPublisherRequest $request
   * @param LibraryBookPublisher $publisher
   * @return JsonResponse
   */
  public function destroy(DeleteLibraryBookPublisherRequest $request, LibraryBookPublisher $publisher): JsonResponse
  {
    $publisher->delete();
    return response()->json([
      'saved' => true,
      'message' => 'Publisher Updated Successfully',
    ]);
  }
}
