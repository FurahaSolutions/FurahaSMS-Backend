<?php

namespace Okotieno\ELearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\ELearning\Database\Factories\ELearningCourseContentFactory;
use Okotieno\StudyMaterials\Models\StudyMaterial;
use Okotieno\StudyMaterials\Traits\hasStudyMaterial;

class ELearningCourseContent extends Model
{
    use softDeletes, hasStudyMaterial, HasFactory;

    protected $fillable = [
        'study_material_id',
        'e_learning_topic_id'
    ];

    protected $appends = [
        'study_material'
    ];

    protected static function newFactory()
    {
      return ELearningCourseContentFactory::new();
    }

  public function getStudyMaterialAttribute() {
        return StudyMaterial::find($this->study_material_id);
    }
    public static function saveStudyMaterial($request) {
        self::create([
            'e_learning_topic_id' => $request->e_learning_topic_id,
            'study_material_id' => $request->study_material_id
        ]);
    }
    public function deleteStudyMaterial($request) {

      if($request['study_material_id'] == $this->study_material_id && $request['e_learning_topic_id'] == $this->e_learning_topic_id) {
         $this->delete();
      }
    }

}
