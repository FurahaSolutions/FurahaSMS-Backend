<?php

namespace Okotieno\Files\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilePic extends Model
{
  use softDeletes;

  protected $fillable = [
    'file_document_id',
    'user_id',
    'model'
  ];

  public function fileDocument()
  {
    return $this->belongsTo(FileDocument::class);
  }
}
