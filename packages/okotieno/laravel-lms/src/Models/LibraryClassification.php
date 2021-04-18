<?php

namespace Okotieno\LMS\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryClassification extends Model
{
  public $timestamps = false;
  protected $table = 'library_classifications';
  protected $fillable = [
    'name', 'abbreviation'
  ];

  public static function ofType($abbreviation)
  {
    return self::where('abbreviation', $abbreviation)->first();
  }

  public function libraryClasses()
  {
    return $this->hasMany(LibraryClass::class);
  }
}
