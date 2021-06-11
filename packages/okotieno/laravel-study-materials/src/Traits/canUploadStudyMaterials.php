<?php


namespace Okotieno\StudyMaterials\Traits;


use Okotieno\StudyMaterials\Models\StudyMaterialDoc;

trait canUploadStudyMaterials
{
    public function uploadStudyMaterial() {
        return $this->hasMany(StudyMaterialDoc::class);
    }
}
