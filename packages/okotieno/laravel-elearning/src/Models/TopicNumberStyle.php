<?php

namespace Okotieno\ELearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\ELearning\Database\Factories\TopicNumberStyleFactory;

class TopicNumberStyle extends Model
{
  public $timestamps = false;
  use softDeletes, HasFactory;
  protected $fillable = ['name'];

  protected static function newFactory()
  {
    return TopicNumberStyleFactory::new();
  }

}
