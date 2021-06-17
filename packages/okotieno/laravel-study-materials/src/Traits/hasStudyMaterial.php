<?php

namespace Okotieno\StudyMaterials\Traits;


use Okotieno\StudyMaterials\Models\StudyMaterial;


trait hasStudyMaterial
{
    public function studyMaterialRelation() {

        return $this->belongsTo(StudyMaterial::class);
    }
}
