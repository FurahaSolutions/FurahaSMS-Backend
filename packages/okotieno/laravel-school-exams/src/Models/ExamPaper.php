<?php


namespace Okotieno\SchoolExams\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolCurriculum\Models\UnitLevel;
use Okotieno\SchoolExams\Database\Factories\ExamPaperFactory;

class ExamPaper extends Model
{
  use softDeletes, HasFactory;

  protected static function newFactory()
  {
    return ExamPaperFactory::new();
  }

  protected $fillable = [
    'unit_level_id',
    'name',
    'created_by'
  ];
  protected $appends = ['unit_level_name'];
  protected $hidden = ['access_by_key', 'private', 'available', 'unit_level'];

  public function instructions()
  {
    return $this->hasMany(ExamInstruction::class);
  }

  public function unitLevel()
  {
    return $this->belongsTo(UnitLevel::class);
  }

  public function getUnitLevelNameAttribute()
  {
    return $this->unitLevel->name;
  }

  public function questions()
  {
    return $this->hasMany(ExamPaperQuestion::class);
  }
}
