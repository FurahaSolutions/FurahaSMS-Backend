<?php

namespace Okotieno\SchoolInfrastructure\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\SchoolInfrastructure\Models\Room;
use Okotieno\SchoolInfrastructure\Requests\RoomStoreRequest;
use Okotieno\SchoolInfrastructure\Requests\RoomDeleteRequest;

class RoomsController extends Controller
{
  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request)
  {
    return response()->json(Room::all());
  }

  /**
   *
   * @param Room $room
   * @return JsonResponse
   */

  public function show(Room $room)
  {

    return response()->json($room);
  }

  public function store(RoomStoreRequest $request)
  {
    $newRoomData = array_merge($request->all(), ['created_by' => auth()->id()]);

    return [
      'saved' => true,
      'message' => 'Room Successfully saved',
      'data' => Room::create($newRoomData)
    ];
  }

  /**
   * @param RoomDeleteRequest $request
   * @param Room $room
   * @return array
   */
  public function destroy(RoomDeleteRequest $request, Room $room)
  {
    $room->delete();
    return [
      'saved' => true,
      'message' => 'Room Successfully deleted'
    ];
  }
}
