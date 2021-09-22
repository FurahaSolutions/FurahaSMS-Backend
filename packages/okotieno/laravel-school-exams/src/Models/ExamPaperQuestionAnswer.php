<?php


namespace Okotieno\SchoolExams\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolExams\Database\Factories\ExamPaperQuestionAnswerFactory;

class ExamPaperQuestionAnswer extends Model
{
    use softDeletes, HasFactory;
    protected $fillable = [
        'description',
        'is_correct',
        'exam_paper_question_id'
    ];
    protected $hidden = ['is_correct'];

    protected static function newFactory()
    {
      return ExamPaperQuestionAnswerFactory::new();
    }
}
