<?php

namespace Okotieno\StudyMaterials\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\StudyMaterials\Database\Factories\StudyMaterialDocFactory;

class StudyMaterialDoc extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'type',
    'extension',
    'mme_type',
    'size',
    'file_path'
  ];

  protected static function newFactory()
  {
    return StudyMaterialDocFactory::new();
  }

  public function studyMaterial()
  {
    return $this->belongsTo(StudyMaterial::class);
  }
}
