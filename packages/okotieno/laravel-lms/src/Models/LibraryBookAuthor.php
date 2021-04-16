<?php

namespace Okotieno\LMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\LMS\Database\Factories\LibraryBookAuthorFactory;

/**
 * @method static find(mixed $id)
 */
class LibraryBookAuthor extends Model
{
  use SoftDeletes, HasFactory;

  protected $fillable = ['name'];

  protected static function newFactory()
  {
    return LibraryBookAuthorFactory::new();
  }
}
