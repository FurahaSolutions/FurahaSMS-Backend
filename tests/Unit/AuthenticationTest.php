<?php

namespace Tests\Unit;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\OauthClientSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Okotieno\Students\Models\Student;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    Artisan::call('passport:install');
  }

  /**
   * GET api/oauth/token
   * @group auth
   * @test
   */
  public function users_can_not_authenticate_with_invalid_password()
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
    echo $response->content();
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
    echo $response->content();
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
    echo $response->content();
    $response->assertStatus(200);
    $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
  }

}
