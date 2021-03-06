<?php


namespace Okotieno\Guardians\Models;


use App\Traits\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\Students\Models\Student;
use Okotieno\Guardians\Database\Factories\GuardianFactory;

class Guardian extends Model
{
  use HasFactory;
  use AppUser;

  protected static function newFactory()
  {
    return GuardianFactory::new();
  }

  protected $fillable = ['guardian_id_number'];

  public static function generateIdNumber()
  {
    return self::count() + 1;
  }

  public function students()
  {
    return $this->belongsToMany(Student::class);
  }
}
