<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 3/7/2020
 * Time: 7:00 PM
 */

namespace Okotieno\Files\Traits;

use Okotieno\Files\Models\FileDocument;

trait CanSaveFileDocument
{
  public function uploadFileDocument()
  {
    return $this->hasMany(FileDocument::class);
  }
}
