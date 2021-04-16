<?php


namespace Okotieno\ELearning\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\ELearning\Models\TopicLearningOutcome;

class TopicLearningOutcomeFactory extends Factory
{
  protected $model = TopicLearningOutcome::class;

  public function definition()
  {
    return [
      'description' => $this->faker->sentence,
      'e_learning_topic_id' => ELearningTopic::factory()->create()->id,
    ];
  }
}
