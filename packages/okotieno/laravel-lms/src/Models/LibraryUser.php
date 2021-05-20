<?php


namespace Okotieno\LMS\Models;

use App\Traits\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\LMS\Database\Factories\LibraryUserFactory;
use Okotieno\LMS\Traits\CanBorrowBook;

class LibraryUser extends Model
{
  use AppUser, CanBorrowBook;
  protected $fillable = ['user_id'];
  use HasFactory;

  protected $casts = [
    'suspended' => 'boolean',
  ];

  public static function newFactory()
  {
    return LibraryUserFactory::new();
  }
}
