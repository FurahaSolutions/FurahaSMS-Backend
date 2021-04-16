<?php


namespace Okotieno\ELearning\Traits;


use Okotieno\ELearning\Models\ELearningTopic;

trait HasELearningTopic
{
  public function eLearningTopic()
  {
    return $this->belongsTo(ELearningTopic::class);
  }
}
