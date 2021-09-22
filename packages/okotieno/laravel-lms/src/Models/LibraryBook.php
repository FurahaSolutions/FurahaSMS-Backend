<?php

namespace Okotieno\LMS\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Okotieno\LMS\Database\Factories\LibraryBookFactory;

/**
 * @method static find(mixed $get)
 */
class LibraryBook extends Model
{
  use SoftDeletes, HasFactory;

  protected static function newFactory()
  {
    return LibraryBookFactory::new();
  }

  protected $fillable = ['title', 'publication_date', 'ISBN'];

  public static function filter($request, $limit = 10)
  {
    $query = DB::table('library_books AS lb');
    if ($request->author !== null) {
      $query = $query->leftJoin('library_book_library_book_author AS lb_lba', 'lb.id', '=', 'lb_lba.library_book_id');
    }
    if ($request->publisher !== null) {
      $query = $query->leftJoin('library_book_library_book_publisher AS lb_lbp', 'lb.id', '=', 'lb_lbp.library_book_id');
    }
    if ($request->author !== null) {
      $query = $query->leftJoin('library_book_authors as lba', 'lba.id', '=', 'lb_lba.library_book_author_id');
    }
    if ($request->publisher !== null) {
      $query = $query->leftJoin('library_book_publishers AS lbp', 'lbp.id', '=', 'lb_lbp.library_book_publisher_id');
    }
    if ($request->tag !== null) {
      $query = $query->leftJoin('library_book_library_book_tag AS lb_lbt', 'lb.id', '=', 'lb_lbt.library_book_id')->leftJoin('library_book_tags AS lbt', 'lbt.id', '=', 'lb_lbt.library_book_tag_id');
    }
    if ($request->title !== null) {
      $query = $query->where('lb.title', 'LIKE', '%' . $request->title . '%');
    }
    if ($request->author !== null) {
      $query = $query->where('lba.name', 'LIKE', '%' . $request->author . '%');
    }
    if ($request->publisher !== null) {
      $query = $query->where('lbp.name', 'LIKE', '%' . $request->publisher . '%');
    }


    if ($request->tag != null) {
      $query = $query->where('lbt.name', 'LIKE', '%' . $request->tag . '%');
    }
    return $query->limit($limit)->select('lb.id AS id');
  }

  public static function collectionDetails(Collection $books)
  {
    return $books->map(function ($book) {
      return $book->details();
    });
  }

  public function details()
  {
    $book = $this;
    $checked_out = BookIssue::findMany($book->libraryBookItems
      ->pluck('id'))
      ->where('returned_date', NULL)->count();
    $count = $book->libraryBookItems->count();
    $reserves = $book->minimum_reserve;
    return [
      'id' => $book->id,
      'title' => $book->title,
      'ISBN' => $book->ISBN,
      'publishers' => $book->libraryBookPublishers->pluck('name'),
      'publication_date' => $book->publication_date,
      'volume' => $book->volume,
      'book_items' => $book->libraryBookItems,
      'category' => $book->libraryClasses->pluck('name'),
      "reserves" => $reserves,
      "available" => $count - $reserves - $checked_out,
      "library_class_ids" => $book->libraryClasses->pluck('id'),
      "max_borrowing_days" => $book->max_borrowing_days,
      "max_borrowing_hours" => $book->max_borrowing_hours,
      "overdue_charge_per_hour" => $book->overdue_charge_per_hour,
      "count" => $count,
      "checked_out_count" => $checked_out
    ];
  }

  public function libraryBookTags()
  {
    return $this->BelongsToMany(LibraryBookTag::class);
  }

  public function libraryBookAuthors()
  {
    return $this->BelongsToMany(LibraryBookAuthor::class);
  }

  public function libraryClasses()
  {
    return $this->belongsToMany(LibraryClass::class);
  }

  public function libraryBookItems()
  {
    return $this->hasMany(LibraryBookItem::class);
  }

  public function libraryBookPublishers()
  {
    return $this->BelongsToMany(LibraryBookPublisher::class);
  }
}
