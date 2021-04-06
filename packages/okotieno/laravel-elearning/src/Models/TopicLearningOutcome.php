<?php

namespace Okotieno\ELearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\ELearning\Database\Factories\TopicLearningOutcomeFactory;
use Okotieno\ELearning\Traits\HasELearningTopic;

class TopicLearningOutcome extends Model
{
  use softDeletes, HasFactory, HasELearningTopic;

  protected $fillable = [
    'description',
    'e_learning_topic_id',
  ];

  protected static function newFactory()
  {
    return TopicLearningOutcomeFactory::new();
  }

}
