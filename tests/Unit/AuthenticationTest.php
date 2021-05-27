<?php

namespace Tests\Unit;

use App\Models\PasswordToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Okotieno\LMS\Models\LibraryUser;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\PermissionsAndRoles\Models\Role;
use Okotieno\Students\Models\Student;
use Tests\TestCase;

class AuthenticationTest extends TestCase
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
   * GET api/oauth/token
   * @group auth
   * @test
   */
  public function users_cannot_authenticate_with_invalid_password()
  {
    $personalAccessToken = DB::table('oauth_clients')
      ->where('password_client', '=', 1)->first();
    $user = User::factory()->create();
    $response = $this->postJson('/api/oauth/token', [
      'username' => $user->email,
      'password' => 'wrong-password',
      'grant_type' => 'password',
      'client_id' => $personalAccessToken->id,
      'client_secret' => $personalAccessToken->secret,
      'scope' => '',
    ]);
    $response->assertStatus(401);
    $response->assertSeeText('Invalid username or password');
  }

  /**
   * GET api/oauth/token
   * @group auth
   * @test
   */
  public function users_can_authenticate_using_passport()
  {
    $personalAccessToken = DB::table('oauth_clients')
      ->where('password_client', '=', 1)->first();
    $user = User::factory()->create();
    $response = $this->postJson('/api/oauth/token', [
      'username' => $user->email,
      'password' => 'password',
      'grant_type' => 'password',
      'client_id' => $personalAccessToken->id,
      'client_secret' => $personalAccessToken->secret,
      'scope' => '',
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
  }

  /**
   * GET api/oauth/token
   * @group auth
   * @test
   */
  public function users_can_authenticate_using_passport_with_admission_number()
  {
    $personalAccessToken = DB::table('oauth_clients')
      ->where('password_client', '=', 1)->first();
    $student = Student::factory()->create();
    $response = $this->postJson('/api/oauth/token', [
      'username' => $student->student_school_id_number,
      'password' => 'password',
      'grant_type' => 'password',
      'client_id' => $personalAccessToken->id,
      'client_secret' => $personalAccessToken->secret,
      'scope' => '',
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
  }

  /**
   * GET api/users/auth/logout
   * @group auth
   * @test
   */
  public function authenticated_users_can_log_off()
  {
    $personalAccessToken = DB::table('oauth_clients')
      ->where('password_client', '=', 1)->first();
    $student = Student::factory()->create();
    $response = $this->postJson('/api/oauth/token', [
      'username' => $student->student_school_id_number,
      'password' => 'password',
      'grant_type' => 'password',
      'client_id' => $personalAccessToken->id,
      'client_secret' => $personalAccessToken->secret,
      'scope' => '',
      'remember_me' => true
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

    $this->getJson('api/users/auth/logout')
      ->assertStatus(401);
    $this->actingAs($this->user, 'api')->getJson('api/users/auth/logout')
      ->assertStatus(200);

    $this->withHeaders(['Authentication' => 'Bearer ' . $response->json('access_token')])
      ->getJson('api/users/auth/logout')
      ->assertStatus(200);
    // TODO check why revoke token does not invalidate user
//    $this->withHeaders(['Authentication' => 'Bearer '.$response->json('access_token')])
//      ->getJson('api/users/auth/logout')
//      ->assertStatus(401);
  }

  /**
   * Test get '/api/users/auth'
   *
   * @return void
   * @group auth
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

  /**
   * Test get '/api/users/auth'
   *
   * @return void
   * @group auth
   * @group get-request
   * @group authentication
   * @test
   *
   */

  public function preflight_request_passes_successfully()
  {
    $this->actingAs($this->user)->options('/api/users/auth')
      ->assertStatus(200)
      ->assertHeader('Access-Control-Allow-Origin');
  }

  /**
   * Test POST '/api/oauth/token' with incorrect token
   *
   * @return void
   * @group auth
   * @group post-request
   * @group authentication
   *
   */

  public function testApiAuthenticationFailsWithInCorrectCredentials()
  {
    $response = $this->post('/api/oauth/token', [
      'grant_type' => 'password',
      'client_id' => 2,
      'client_secret' => $this->token->secret,
      'username' => $this->randomEmail,
      'password' => 'Invalid password',
      'scope' => '',
    ]);
    $response->assertStatus(401);
  }

  /**
   * Test POST '/api/oauth/token' with incorrect token
   *
   * @return void
   * @group auth
   * @group post-request
   * @group authentication
   *
   */

  public function testApiAuthenticationFailsWithCorrectCredentialsInvalidToken()
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
   * Test POST '/api/oauth/token' with correct credentials
   *
   * @return void
   * @group auth
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
   * Test get '/api/users/auth'
   *
   * @return void
   * @group get-request
   * @group auth
   * @group authentication
   *
   */
  public function testUserRolesAndPermissionsAreIncludedInApi()
  {
    $role = Role::create(['name' => 'some role']);
    Role::create(['name' => 'another role']);
    $permission = Permission::create(['name' => 'some permission']);
    Permission::create(['name' => 'another permission']);

    $role->permissions()->save($permission);
    $this->user->assignRole('some role');
    $this->actingAs($this->user, 'api')
      ->get('/api/users/auth')
      ->assertJsonFragment(['some role'])
      ->assertJsonFragment(['some permission'])
      ->assertJsonMissing(['another permission']);
  }

  /**
   * Test get '/api/users/auth'
   *
   * @return void
   * @group auth
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
   * @group auth
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
   * GET api/user/auth
   * @group auth
   * @test
   */
  public function authenticated_user_can_retrieve_own_profile()
  {
    $this->actingAs($this->user, 'api')
      ->get('api/users/auth')
      ->assertStatus(200)
      ->assertJsonStructure(['id', 'first_name', 'last_name', 'permissions', 'roles']);
  }

  /**
   * GET api/user/auth
   * @group auth
   * @test
   */
  public function authenticated_users_with_library_access_should_have_permission_to_access_library()
  {
    LibraryUser::factory()->state(['user_id' => $this->user->id])->create();
    $this->actingAs($this->user, 'api')
      ->get('api/users/auth')
      ->assertSeeText(['access library']);
  }

  /**
   * GET api/password/email
   * @group auth
   * @group post-request
   * @test
   */
  public function error_422_if_user_tries_to_reset_password_without_email()
  {
    $this->postJson('api/password/email', [])
      ->assertStatus(422);
  }

  /**
   * GET api/password/email
   * @group auth
   * @group post-request
   * @test
   */
  public function user_can_receive_reset_password_email()
  {
    $user = User::factory()->create();
    $this->postJson('api/password/email', ['email' => $user->email])
      ->assertStatus(200);
  }

  /**
   * GET api/password/email
   * @group auth
   * @group post-request
   * @test
   */
  public function error_403_if_unknown_email_while_requesting_for_a_password_reset()
  {
    $user = User::factory()->make();
    $this->postJson('api/password/email', ['email' => $user->email])
      ->assertStatus(403);
  }

  /**
   * GET api/password/reset
   * @group auth-1
   * @group post-request
   * @test
   */
  public function user_can_reset_password_using_valid_old_password()
  {
    $oldPassword = $this->faker->password;
    $newPassword = $this->faker->password;
    $this->user->setPassword($oldPassword);
    $this->actingAs($this->user, 'api')->postJson('api/password/reset',
      [
        'email' => $this->user->email,
        'old_password' => $oldPassword,
        'new_password' => $newPassword,
        'new_password_confirmation' => $newPassword,
      ])->assertStatus(200);
  }

  /**
   * GET api/password/reset
   * @group auth-1
   * @group post-request
   * @test
   */
  public function user_cannot_reset_password_using_invalid_old_password()
  {
    $oldPassword = $this->faker->password;
    $newPassword = $this->faker->password;
    $this->user->setPassword($this->faker->password);
    $this->actingAs($this->user, 'api')->postJson('api/password/reset',
      [
        'email' => $this->user->email,
        'old_password' => $oldPassword,
        'new_password' => $newPassword,
        'new_password_confirmation' => $newPassword,
      ])->assertStatus(403);
  }

  /**
   * GET api/password/reset
   * @group auth-1
   * @group post-request
   * @test
   */
  public function user_cannot_reset_password_using_invalid_token()
  {
    $token = bcrypt($this->faker->password);
    $newPassword = $this->faker->password;
    $this->user->setPassword($this->faker->password);
    $this->actingAs($this->user, 'api')->postJson('api/password/reset',
      [
        'email' => $this->user->email,
        'token' => $token,
        'new_password' => $newPassword,
        'new_password_confirmation' => $newPassword,
      ])->assertStatus(403);
  }

  /**
   * GET api/password/reset
   * @group auth-1
   * @group post-request
   * @test
   */
  public function user_can_reset_password_using_valid_token()
  {
    $token = bcrypt($this->faker->password);
    PasswordToken::factory()->state(['token' => $token, 'user_id' => $this->user->id])->create();
    $newPassword = $this->faker->password;
    $this->user->setPassword($this->faker->password);
    $this->actingAs($this->user, 'api')->postJson('api/password/reset',
      [
        'email' => $this->user->email,
        'token' => $token,
        'new_password' => $newPassword,
        'new_password_confirmation' => $newPassword,
      ])->assertStatus(200);
  }

}
