<?php


namespace Okotieno\AcademicYear\Traits;


use Okotieno\AcademicYear\Models\ArchivableItem;

trait Archivable
{
  public function archivableItems()
  {
    return $this->belongsToMany(ArchivableItem::class);
  }

}
