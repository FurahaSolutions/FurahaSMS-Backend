<?php

namespace Okotieno\LMS\Models;


use Okotieno\LMS\Database\Factories\LibraryClassificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryClassification extends Model
{
  use HasFactory;
  protected static function newFactory()
  {
    return LibraryClassificationFactory::new();
  }

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
