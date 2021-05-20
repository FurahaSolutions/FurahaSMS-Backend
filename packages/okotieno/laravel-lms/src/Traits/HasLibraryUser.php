<?php

namespace Okotieno\LMS\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Okotieno\LMS\Models\LibraryUser;

trait HasLibraryUser
{
  public function libraryUser(): HasOne
  {
    return $this->hasOne(LibraryUser::class);
  }

  public function getLibraryUserIdAttribute()
  {
    return $this->libraryUser ? $this->libraryUser->id : null;
  }
  public function getCanBorrowBookAttribute()
  {
    return $this->libraryUser ? $this->libraryUser->can_borrow_book : null;
  }
  public function getLibrarySuspendedAttribute()
  {
    return $this->libraryUser ? $this->libraryUser->suspended : null;
  }
}
