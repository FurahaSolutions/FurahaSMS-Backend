<?php

namespace Okotieno\LMS\Models;


use Okotieno\LMS\Database\Factories\LibraryBookTagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBookTag extends Model
{
  use HasFactory;
  protected static function newFactory()
  {
    return LibraryBookTagFactory::new();
  }

  public $timestamps = false;
  protected $fillable = ['name', 'active'];
}
