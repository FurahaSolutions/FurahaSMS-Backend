<?php

namespace Okotieno\ELearning\Traits;

use Okotieno\ELearning\Models\TopicLearningOutcome;

trait hasLearningOutcomes
{
  public function learningOutcomes()
  {
    return $this->hasMany(TopicLearningOutcome::class);
  }

  public function getExpectedLearningOutcomesAttribute()
  {
    return $this->learningOutcomes;
  }
}
