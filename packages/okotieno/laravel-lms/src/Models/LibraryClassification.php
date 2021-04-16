<?php

namespace Okotieno\LMS\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryClassification extends Model
{
  public $timestamps = false;
  protected $table = 'library_classifications';
  protected $fillable = [
    'name', 'abbr'
  ];

  public static function ofType($abbr)
  {
    return self::where('abbr', $abbr)->first();
  }

  public function libraryClasses()
  {
    return $this->hasMany(LibraryClass::class);
  }
}
