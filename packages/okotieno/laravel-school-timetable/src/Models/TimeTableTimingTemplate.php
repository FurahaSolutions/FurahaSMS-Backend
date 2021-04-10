<?php

namespace Okotieno\TimeTable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\TimeTable\Database\Factories\TimeTableTimingTemplateFactory;

class TimeTableTimingTemplate extends Model
{
  use HasFactory;
  protected static function newFactory()
  {
    return TimeTableTimingTemplateFactory::new();
  }

  protected $fillable = ['description'];

  public function timings()
  {
    return $this->belongsToMany(TimeTableTiming::class);
  }
}
