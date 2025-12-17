<?php

namespace App\Services\Teacher;

use App\Models\Exam;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class ExamAccessService
{
    public function authorizeTeacherExam(Exam $exam): void
    {
        if ($exam->teacher_id !== auth::id()) {
            throw new AuthorizationException('Unauthorized exam access');
        }
    }

    public function detectExamMode(Exam $exam): string
    {
        return $exam->classroom_id ? 'class' : 'public';
    }
}
