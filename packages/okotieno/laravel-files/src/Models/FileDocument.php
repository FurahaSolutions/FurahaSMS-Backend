<?php

namespace Okotieno\Files\Models;

use Okotieno\Files\Database\Factories\FileDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FileDocument extends Model
{
  use HasFactory;
  protected static function newFactory()
  {
    return FileDocumentFactory::new();
  }

  protected $fillable = [
    'name',
    'type',
    'extension',
    'mme_type',
    'size',
    'file_path'
  ];

}
