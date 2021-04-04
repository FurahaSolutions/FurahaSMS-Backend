<?php


namespace Okotieno\AcademicYear\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Okotieno\AcademicYear\Models\Holiday;

class HolidaySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $holidays = [
      ["name" => "New Year's Day", "date" => "Fri, 1 Jan 2000", "confirmation_variance" => 0],
      ["name" => "Good Friday", "date" => "Fri, 2 Apr 2000", "confirmation_variance" => 0],
      ["name" => "Easter Monday", "date" => "Mon, 5 Apr 2000", "confirmation_variance" => 0],
      ["name" => "Labour Day", "date" => "Sat, 1 May 2000", "confirmation_variance" => 0],
      ["name" => "Eid al-Fitr", "date" => "12 May 2000", "confirmation_variance" => 1],
      ["name" => "Madaraka Day", "date" => "Tue, 1 Jun 2000", "confirmation_variance" => 0],
      ["name" => "Eid al-Adha", "date" => "19 Jul 2000", "confirmation_variance" => 1],
      ["name" => "Huduma Day", "date" => "Mon, 11 Oct 2000", "confirmation_variance" => 0],
      ["name" => "Mashujaa Day", "date" => "Wed, 20 Oct 2000", "confirmation_variance" => 0],
      ["name" => "Jamhuri Day", "date" => "Mon, 13 Dec 2000", "confirmation_variance" => 0],
      ["name" => "Christmas Day", "date" => "Sat, 25 Dec 2000", "confirmation_variance" => 0],
      ["name" => "Utamaduni Day", "date" => "Mon, 27 Dec 2000", "confirmation_variance" => 0]
    ];

    foreach ($holidays as $holiday) {
      Holiday::create([
        "name" => $holiday["name"],
        "confirmation_variance" => $holiday["confirmation_variance"],
        "occurs_on" => Carbon::create($holiday['date'])
      ]);
    }
  }
}
