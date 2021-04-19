<?php

namespace Okotieno\TimeTable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\TimeTable\Database\Factories\WeekDayFactory;

class WeekDay extends Model
{
  protected $fillable = ['name', 'abbreviation', 'active'];
  use HasFactory;
  protected static function newFactory()
  {
    return WeekDayFactory::new();
  }
}
