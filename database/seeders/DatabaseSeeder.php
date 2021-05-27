<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Okotieno\AcademicYear\Database\Seeders\HolidaySeeder;


class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call(OauthClientSeeder::class);
    $this->call(UsersTableSeeder::class);
    $this->call(PermissionAndRolesSeeder::class);
    $this->call(ClassLevelSeeder::class);
    $this->call(TimeTableSeeder::class);
    $this->call(TeachersSeeder::class);
    $this->call(UnitsSeeder::class);
    $this->call(StreamSeeder::class);
    $this->call(RoomsSeeder::class);
    $this->call(AcademicYearsSeeder::class);
    $this->call(StudentsSeeder::class);
    $this->call(HolidaySeeder::class);
    $this->call(LibrarySeeder::class);
  }

}
