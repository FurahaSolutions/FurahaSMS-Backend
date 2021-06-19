<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response as Psr7Response;
use Okotieno\Students\Models\Student;
use Psr\Http\Message\ServerRequestInterface;


class AuthController extends Controller
{
  use HandlesOAuthErrors;

  /**
   * The authorization server.
   *
   * @var \League\OAuth2\Server\AuthorizationServer
   */
  protected $server;


  public function __construct(AuthorizationServer $server)
  {

    $this->server = $server;
  }

  /**
   * Login user and create token
   *
   * @param ServerRequestInterface $request
   * @return JsonResponse [string] access_token
   * @throws OAuthServerException
   */
  public function login(ServerRequestInterface $request)
  {
    $credentials = [
      'password' => request()->password,
      'email' => request()->username,
    ];

    $validAuth = false;

    if (Auth::attempt($credentials)) {
      return $this->withErrorHandling(function () use ($request) {
        return $this->convertResponse(
          $this->server->respondToAccessTokenRequest($request, new Psr7Response)
        );
      });
    }


    if (!$validAuth) {
      $loginByAdmissionNumber = Student::where('student_school_id_number', request()->username)->first();
      if ($loginByAdmissionNumber) {
        $credentials = [
          'id' => $loginByAdmissionNumber->user->id,
          'password' => request()->password,
        ];
        if (Auth::attempt($credentials)) {
          $validAuth = true;
        }
      }
    }


    if ($validAuth) {
      $user = auth()->user();

      $tokenResult = $user->createToken('Personal Access Token');
      $token = $tokenResult->token;
      if (request()->remember_me)
        $token->expires_at = Carbon::now()->addWeeks(1);
      $token->save();
      return response()->json([
        'access_token' => $tokenResult->accessToken,
        'token_type' => 'Bearer',
        'expires_in' => Carbon::parse(
          $tokenResult->token->expires_at
        )->toDateTimeString(),
        'expires_at' => Carbon::parse(
          $tokenResult->token->expires_at
        )->toDateTimeString()
      ]);
    }

    return response()->json([
      'message' => 'Invalid username or password'
    ], 401);
  }

  /**
   * Logout user (Revoke the token)
   *
   * @param Request $request
   * @return JsonResponse [string] message
   */
  public function logout(Request $request)
  {
    auth('web')->logout();
    $userTokens = $request->user()->tokens()->where(['revoked' => false]);
    if ($userTokens->count() > 0) {
      $userTokens->update(['revoked' => true]);
    }
    return response()->json([
      'saved' => true,
      'message' => 'Successfully logged out'
    ]);
  }

  /**
   * Get the authenticated User
   *
   * @param Request $request
   * @return JsonResponse [json] user object
   */
  public function user(Request $request)
  {
    $user = $request->user();
    $response = $user->toArray();
    $permissions = $user->getAllPermissions()->pluck('name')->toArray();
    if ($user->libraryUser) {
      $permissions = [...$permissions, 'access library'];
    }
    $response['library_user'] = !!$user->libraryUser;
    $response['permissions'] = $permissions;
    $response['roles'] = $user->roles->pluck('name')->toArray();
    return response()->json($response);
  }
}
