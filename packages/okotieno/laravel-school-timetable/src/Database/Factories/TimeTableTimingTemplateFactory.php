<?php


namespace Okotieno\TimeTable\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Okotieno\ELearning\Models\ELearningCourse;
use Okotieno\ELearning\Models\ELearningTopic;
use Okotieno\ELearning\Models\TopicNumberStyle;
use Okotieno\TimeTable\Models\TimeTable;
use Okotieno\TimeTable\Models\TimeTableTimingTemplate;

class TimeTableTimingTemplateFactory extends Factory
{
  protected $model = TimeTableTimingTemplate::class;

  public function definition()
  {
    return [
      'description' => $this->faker->sentence
    ];
  }
}
