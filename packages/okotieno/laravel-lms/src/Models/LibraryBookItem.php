<?php

namespace Okotieno\LMS\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;
use Okotieno\LMS\Database\Factories\LibraryBookItemFactory;

class LibraryBookItem extends Model
{
  use SoftDeletes, HasFactory;

  protected static function newFactory()
  {
    return LibraryBookItemFactory::new();
  }

  protected $fillable = ['ref', 'procurement_date', 'reserved', 'library_book_id'];

  public function markAsReturned()
  {
    $book_issue = BookIssue::where('library_book_item_id', $this->id)
      ->whereNull('returned_date')
      ->first();
    if ($book_issue === null) {
      $error = ValidationException::withMessages(
        ['ref' => ['Book has not been borrowed']]
      );
      throw $error;
    }
    $book_issue->returned_date = Carbon::now();
    $book_issue->save();

  }

  public function libraryBook()
  {
    return $this->belongsTo(LibraryBook::class);
  }

  public function bookIssues()
  {
    return $this->hasMany(BookIssue::class);
  }

  public function scopeBorrowed($query)
  {
    return $query->whereHas('bookIssues', function ($q) {
      $q->whereNull('returned_date');
    });
  }
}
