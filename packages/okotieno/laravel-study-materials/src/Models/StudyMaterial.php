<?php

namespace Okotieno\StudyMaterials\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\StudyMaterials\Database\Factories\StudyMaterialFactory;

class StudyMaterial extends Model
{
  use HasFactory;

  protected $hidden = ['study_material_doc'];
  protected $appends = ['file_document_info'];
  protected $fillable = [
    'title',
    'study_material_doc_id',
    'public',
    'active'
  ];

  protected static function newFactory()
  {
    return StudyMaterialFactory::new();
  }

  public function studyMaterialDoc()
  {
    return $this->BelongsTo(StudyMaterialDoc::class);
  }

  public function getFileDocumentInfoAttribute()
  {
    return $this->studyMaterialDoc;
  }
}
