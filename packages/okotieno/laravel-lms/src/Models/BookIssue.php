<?php

namespace Okotieno\LMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\LMS\Database\Factories\LibraryBookIssueFactory;

class BookIssue extends Model
{
  use HasFactory;

  protected static function newFactory()
  {
    return LibraryBookIssueFactory::new();
  }

  public function libraryUser()
  {
    return $this->belongsTo(LibraryUser::class);
  }

  public function libraryBookItem()
  {
    return $this->belongsTo(LibraryBookItem::class);

  }

  public function getCategoriesAttribute()
  {
    return $this->libraryBookItem->libraryBook->libraryClasses->pluck('name');
  }
}
