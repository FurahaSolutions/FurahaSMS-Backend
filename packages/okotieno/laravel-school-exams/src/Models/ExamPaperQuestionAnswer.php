<?php


namespace Okotieno\SchoolExams\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamPaperQuestionAnswer extends Model
{
    use softDeletes;
    protected $fillable = [
        'description',
        'is_correct',
    ];
}
