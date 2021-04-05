<?php

namespace Okotieno\SchoolCurriculum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolCurriculum\Database\Factories\UnitLevelFactory;
use Okotieno\SchoolCurriculum\Traits\TaughtInClassLevel;

class UnitLevel extends Model
{
  use softDeletes, TaughtInClassLevel, HasFactory;

  protected $fillable = ['name', 'level', 'unit_id'];
  public $timestamps = false;
  protected $hidden = ['deleted_at'];

  protected static function newFactory()
  {
    return UnitLevelFactory::new();
  }

  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }

  public function semesters()
  {
    return $this->belongsToMany(Semester::class);
  }

}
