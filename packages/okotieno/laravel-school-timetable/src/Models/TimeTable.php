<?php

namespace Okotieno\TimeTable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\TimeTable\Database\Factories\TimeTableFactory;

class TimeTable extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = ['time_table_timing_template_id', 'description', 'academic_year_id'];

    protected static function newFactory()
    {
      return TimeTableFactory::new();
    }

  public function timing() {
        return $this->belongsTo(TimeTableTimingTemplate::class);
    }

    public function lessons(){
        return $this->HasMany(TimeTableLesson::class);
    }
}
