<?php


namespace Okotieno\StudyMaterials\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\StudyMaterials\Models\StudyMaterial;
use Okotieno\StudyMaterials\Models\StudyMaterialDoc;

class StudyMaterialFactory extends Factory
{

  protected $model = StudyMaterial::class;

  public function definition()
  {
    return [
      'title' => $this->faker->name,
      'study_material_doc_id' => StudyMaterialDoc::factory()->create()->id,
      'public' => $this->faker->boolean,
      'active' => $this->faker->boolean
    ];
  }
}
