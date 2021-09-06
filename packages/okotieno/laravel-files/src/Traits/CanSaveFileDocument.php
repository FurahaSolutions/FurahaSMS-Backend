<?php

namespace Okotieno\Files\Traits;

use Okotieno\Files\Models\FileDocument;

trait CanSaveFileDocument
{
  public function uploadFileDocument()
  {
    return $this->hasMany(FileDocument::class, 'uploaded_by');
  }
}
