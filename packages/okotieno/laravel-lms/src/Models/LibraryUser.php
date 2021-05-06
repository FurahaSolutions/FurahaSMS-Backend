<?php


namespace Okotieno\LMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\LMS\Database\Factories\LibraryUserFactory;

class LibraryUser extends Model
{
  protected $fillable = ['user_id'];
  use HasFactory;

  public function newFactory(): LibraryUserFactory
  {
    return LibraryUserFactory::new();
  }
}
