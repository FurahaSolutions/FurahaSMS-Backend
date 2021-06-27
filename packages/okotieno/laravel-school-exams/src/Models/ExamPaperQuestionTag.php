<?php


namespace Okotieno\SchoolExams\Models;


use Okotieno\SchoolExams\Database\Factories\ExamPaperQuestionTagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamPaperQuestionTag extends Model
{
    use softDeletes, HasFactory;
    protected $fillable = [
        'name'
    ];
    public function questions() {
        return $this->belongsToMany(ExamPaperQuestion::class);
    }

    protected static function newFactory()
    {
      return ExamPaperQuestionTagFactory::new();
    }
}
