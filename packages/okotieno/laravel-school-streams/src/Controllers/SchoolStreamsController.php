<?php


namespace Okotieno\SchoolStreams\Controllers;

use App\Http\Controllers\Controller;
use Okotieno\SchoolStreams\Models\Stream;
use Okotieno\SchoolStreams\Requests\CreateStreamRequest;
use Okotieno\SchoolStreams\Requests\DeleteStreamRequest;
use Okotieno\SchoolStreams\Requests\UpdateStreamRequest;

class SchoolStreamsController extends Controller
{
  public function index()
  {
    return response()->json(Stream::all());
  }

  public function show(Stream $classStream)
  {
    return response()->json($classStream);
  }

  public function store(CreateStreamRequest $request)
  {
    return response()->json([
      'saved' => 'true',
      'message' => 'Successfully created stream',
      'data' => Stream::create($request->all())
    ])->setStatusCode(201);
  }

  public function update(UpdateStreamRequest $request, Stream $classStream)
  {

    $classStream->update($request->all());
    return response()->json([
      'saved' => 'true',
      'message' => 'Successfully created stream',
      'data' => $classStream
    ]);
  }

  public function destroy(DeleteStreamRequest $request, Stream $classStream)
  {
    $classStream->delete();
    return response()->json([
      'saved' => 'true',
      'message' => 'Successfully created stream'
    ]);
  }

}
