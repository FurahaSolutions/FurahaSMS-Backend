<?php


namespace Okotieno\Files\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\Files\Models\FileDocument;

class FileDocumentFactory extends Factory
{
  protected $model = FileDocument::class;

  public function definition()
  {
    return [
      'name' => $this->faker->name,
      'type' => $this->faker->fileExtension,
      'extension'=> $this->faker->fileExtension,
      'mme_type'=> $this->faker->fileExtension,
      'size'=> $this->faker->randomNumber(3),
      'file_path'=> $this->faker->filePath(),
    ];
  }
}
