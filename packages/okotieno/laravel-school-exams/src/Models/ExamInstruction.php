<?php

namespace Okotieno\SchoolExams\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolExams\Database\Factories\ExamInstructionFactory;

class ExamInstruction extends Model
{
  use softDeletes, HasFactory;

  protected $fillable = [
    'exam_paper_id',
    'description',
    'position'
  ];

  protected static function newFactory()
  {
    return ExamInstructionFactory::new();
  }
}
