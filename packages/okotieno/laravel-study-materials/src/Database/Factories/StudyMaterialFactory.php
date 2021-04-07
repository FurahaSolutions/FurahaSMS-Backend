<?php


namespace Okotieno\StudyMaterials\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\StudyMaterials\Models\StudyMaterialDoc;

class StudyMaterialDocFactory extends Factory
{

    protected $model = StudyMaterialDoc::class;
    public function definition()
    {
        return [
          'name' => $this->faker->name,
          'type' => $this->faker->colorName,
          'extension' => $this->faker->fileExtension,
          'mme_type' => $this->faker->mimeType,
          'size' => $this->faker->numberBetween(1000, 2000),
          'file_path' => $this->faker->url
        ];
    }
}
