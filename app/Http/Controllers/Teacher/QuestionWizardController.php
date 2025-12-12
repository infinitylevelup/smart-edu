<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;

class QuestionWizardController extends Controller
{
    // فرم ایجاد سوال جدید (Wizard)
    public function create(Exam $exam)
    {
        // همان منطق کنترل دسترسی QuestionController
        (new QuestionController)->authorizeTeacherExam($exam);

        $examMode = (new QuestionController)->detectExamMode($exam);

        $subjects = null;
        if ($examMode === 'multi_subject') {
            $subjects = $exam->subjects()->get(['id', 'title_fa']);
        }

        return view('dashboard.teacher.exams.questions.wizard.create', [
            'exam'     => $exam,
            'subjects' => $subjects,
            'examMode' => $examMode,
        ]);
    }

    // فرم ویرایش سوال (Wizard)
    public function edit(Exam $exam, Question $question)
    {
        (new QuestionController)->authorizeTeacherExam($exam);

        // امنیت: سوال باید متعلق به همین آزمون باشد
        abort_if($question->exam_id !== $exam->id, 404);

        $examMode = (new QuestionController)->detectExamMode($exam);

        $subjects = null;
        if ($examMode === 'multi_subject') {
            $subjects = $exam->subjects()->get(['id', 'title_fa']);
        }

        $questions = $exam->questions()->latest()->get();

        return view('dashboard.teacher.exams.questions.wizard.edit', [
            'exam'      => $exam,
            'question'  => $question,
            'subjects'  => $subjects,
            'examMode'  => $examMode,
            'questions' => $questions,
        ]);
    }
}
