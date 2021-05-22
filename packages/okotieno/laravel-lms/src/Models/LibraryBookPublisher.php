<?php

namespace Okotieno\LMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\Files\Traits\HasProfilePics;
use Okotieno\LMS\Database\Factories\LibraryBookPublisherFactory;

class LibraryBookPublisher extends Model
{
  use HasProfilePics, HasFactory;

  protected $fillable = ['name', 'biography'];

  protected $appends = ['profile_pic_id'];

  protected static function newFactory()
  {
    return LibraryBookPublisherFactory::new();
  }

}
