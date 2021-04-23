<?php

namespace Okotieno\LMS\Models;


use Okotieno\LMS\Database\Factories\LibraryClassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryClass extends Model
{
  use HasFactory;
  protected static function newFactory()
  {
    return LibraryClassFactory::new();
  }

  public $timestamps = false;
  protected $table = 'library_classes';

  protected $fillable = [
    'name', 'library_classification_id', 'library_class_id', 'class'
  ];

  protected $hidden = ["library_classes"];

  protected $appends = ['classes'];

  public function libraryClasses()
  {
    return $this->hasMany(LibraryClass::class);
  }

  public function libraryClassificationSystem()
  {
    return $this->belongsTo(LibraryClassification::class);
  }

  public function getClassesAttribute()
  {
    return $this->libraryClasses->map(function ($item) {
      return [
        'id' => $item['id'],
        'class' => $item['class'],
        'name' => $item['name'],
        'classes' => $item['classes'],
      ];
    });
  }

}
