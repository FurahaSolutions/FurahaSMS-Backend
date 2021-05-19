<?php

namespace Okotieno\SchoolCurriculum\Traits;

use Okotieno\Students\Models\Student;

trait TakenByStudents
{
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
