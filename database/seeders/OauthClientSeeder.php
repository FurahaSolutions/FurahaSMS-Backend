<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('oauth_personal_access_clients')->insert([
      'client_id' => 1,
      'created_at' => '2021-02-11 15:35:52',
      'updated_at' => '2021-02-11 15:35:52',
    ]);
    DB::table('oauth_clients')->insert([
      [
        'id' => 1,
        'user_id' => NULL,
        'name' => 'Laravel Personal Access Client',
        'secret' => 'ty26GCdwbGFKQtgeTAxTxMey4j7JnkpJA05jE4Rt',
        'redirect' => 'http://localhost',
        'personal_access_client' => 1,
        'password_client' => 0,
        'revoked' => 0,
        'created_at' => '2021-02-11 15:35:52',
        'updated_at' => '2021-02-11 15:35:52',
        'provider' => NULL
      ],
      [
        'id' => 2,
        'user_id' => NULL,
        'name' => 'Laravel Personal Access Client',
        'secret' => 'duK0bplTPn2BeyrsjX1939Y9OPIjPytEFUUNwjqD',
        'redirect' => 'http://localhost',
        'personal_access_client' => 0,
        'password_client' => 1,
        'revoked' => 0,
        'created_at' => '2021-02-11 15:35:52',
        'updated_at' => '2021-02-11 15:35:52',
        'provider' => 'users'

      ]
    ]);
  }
}
