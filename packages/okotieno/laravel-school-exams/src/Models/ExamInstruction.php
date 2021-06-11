<?php

namespace Okotieno\SchoolExams\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamInstruction extends Model
{
    use softDeletes;
    protected $fillable = [
        'exam_paper_id',
        'description',
        'position'
    ];
}
