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

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function libraryBookItem()
  {
    return $this->belongsTo(LibraryBookItem::class);

  }
}
