<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServerRunningTest extends TestCase
{


  /**
   * @var Model|Builder|object|null
   */
  private $token;
  private string $randomEmail;
  private string $password;

  protected function setUp(): void
  {
    parent::setUp();
    Artisan::call('passport:install');
    $this->token = DB::table('oauth_clients')
      ->where('name', "Laravel Password Grant Client")->first();
    $this->randomEmail = $this->faker->email;
    $this->password = $this->faker->password;
    User::factory()->create([
      'email' => $this->randomEmail,
      'password' => bcrypt($this->password)
    ]);
    $this->user = User::where('email', $this->randomEmail)->first();
  }

  /**
   * Test GET '/'
   *
   * @return void
   * @group app
   * @group get-request
   *
   */
  public function testRootRouteStatusOk()
  {
    $response = $this->get('/');
    $response->assertStatus(200);
  }

  /**
   * Test POST '/api/oauth/token' with correct credentials
   *
   * @return void
   * @group app
   * @group post-request
   * @group authentication
   *
   */
  public function testApiAuthenticationOkWithCorrectCredentials()
  {


    $response = $this->post('/api/oauth/token', [
      'grant_type' => 'password',
      'client_id' => $this->token->id,
      'client_secret' => $this->token->secret,
      'username' => $this->randomEmail,
      'password' => $this->password,
      'scope' => '',
    ]);
    $response->assertStatus(200)
      ->assertJsonStructure([
        'expires_in',
        'access_token',
        'refresh_token'
      ]);
  }

  /**
   * Test POST '/api/oauth/token' with incorrect token
   *
   * @return void
   * @group app
   * @group post-request
   * @group authentication
   * @test
   *
   */

  public function api_authentication_fails_with_correct_credentials_invalid_token()
  {
    $response = $this->post('/api/oauth/token', [
      'grant_type' => 'password',
      'client_id' => 2,
      'client_secret' => 'invalid Token',
      'username' => $this->randomEmail,
      'password' => $this->password,
      'scope' => '',
    ]);
    $response->assertStatus(401);

  }

  /**
   * Test POST '/api/oauth/token' with incorrect token
   *
   * @return void
   * @group app
   * @group post-request
   * @group authentication
   * @group test
   *
   */

  public function api_authentication_fails_with_incorrect_credentials()
  {
    $response = $this->post('/api/oauth/token', [
      'grant_type' => 'password',
      'client_id' => 2,
      'client_secret' => $this->token->secret,
      'username' => $this->randomEmail,
      'password' => 'Invalid password',
      'scope' => '',
    ]);
    echo $response->content();
    $response->assertStatus(401);
  }

  /**
   * Test get '/api/users/auth'
   *
   * @return void
   * @group app
   * @group get-request
   * @group authentication
   *
   */

  public function testUserDetailsReturnedIfUserIsLoggedIn()
  {
    $token = $this->user->createToken('Token Name')->accessToken;
    $this->withHeaders([
      'Authorization' => 'Bearer ' . $token
    ])->get('/api/users/auth')
      ->assertStatus(200);

    $response = $this->actingAs($this->user, 'api')->get('/api/users/auth');
    $response->assertStatus(200);
  }

  /**
   * Test get '/api/users/auth' Authorization Token
   *
   * @return void
   * @group app
   * @group get-request
   * @group authentication
   *
   */

  public function testUserDetailsReturnedIfUserProvidesValidBearerToken()
  {
    $token = $this->user->createToken('Token Name')->accessToken;
    $this->withHeaders([
      'Authorization' => 'Bearer ' . $token
    ])->get('/api/users/auth')
      ->assertStatus(200);
  }

  /**
   * Test get '/api/users/auth'
   *
   * @return void
   * @group app
   * @group get-request
   * @group authentication
   *
   */

  public function testAuthFailsIfInvalidTokenProvided()
  {
    $this->withHeaders([
      'Authorization' => 'Bearer Invalid token',
      'Accept' => 'application/json'
    ])->get('/api/users/auth')
      ->assertStatus(401);
  }
}


