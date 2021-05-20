<?php

namespace Okotieno\Files\Models;

use App\Models\ProfilePic;
use Illuminate\Database\Eloquent\Model;
use Okotieno\LMS\Traits\hasFileDocuments;

class FileDocument extends Model
{

  protected $fillable = [
    'name',
    'type',
    'extension',
    'mme_type',
    'size',
    'file_path'
  ];

  public function profilePics()
  {
    return $this->belongsToMany(ProfilePic::class);
  }

}
