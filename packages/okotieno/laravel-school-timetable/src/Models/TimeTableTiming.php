<?php

namespace Okotieno\TimeTable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\TimeTable\Database\Factories\TimeTableTimingFactory;

class TimeTableTiming extends Model
{
  use HasFactory;

  protected static function newFactory()
  {
    return TimeTableTimingFactory::new();
  }

  protected $fillable = ['start', 'end'];
}
