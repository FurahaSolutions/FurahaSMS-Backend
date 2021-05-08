<?php


namespace Okotieno\LMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\LMS\Database\Factories\LibraryUserFactory;

class LibraryUser extends Model
{
  protected $fillable = ['user_id'];
  use HasFactory;

  protected $casts = [
    'blocked' => 'boolean',
  ];

  public static function newFactory()
  {
    return LibraryUserFactory::new();
  }
}
