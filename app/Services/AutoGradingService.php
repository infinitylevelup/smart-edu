<?php

namespace App\Services;

use App\Models\Question;

/**
 * AutoGradingService
 *
 * این سرویس فقط منطق نمره‌دهی را نگه می‌دارد تا کنترلر سبک و پایدار بماند.
 */
class AutoGradingService
{
    /**
     * نمره‌دهی یک سوال بر اساس نوع و پاسخ دانش‌آموز
     *
     * @return array {is_correct: ?bool, score_awarded: int}
     */
    public function grade(Question $question, $studentAnswer): array
    {
        return match ($question->type) {
            Question::TYPE_MCQ        => $this->gradeMcq($question, $studentAnswer),
            Question::TYPE_TRUE_FALSE => $this->gradeTrueFalse($question, $studentAnswer),
            Question::TYPE_FILL_BLANK => $this->gradeFillBlank($question, $studentAnswer),
            Question::TYPE_ESSAY      => [
                'is_correct'   => null,
                'score_awarded'=> 0, // essay دستی تصحیح می‌شود
            ],
            default => [
                'is_correct'   => null,
                'score_awarded'=> 0,
            ],
        };
    }

    /**
     * MCQ:
     * - legacy: correct_option + option_a..d
     * - پاسخ دانش‌آموز معمولاً "a/b/c/d" است
     */
    protected function gradeMcq(Question $q, $ans): array
    {
        $student = is_array($ans) ? ($ans['selected'] ?? null) : $ans;
        $correct = $q->correct_option;

        $isCorrect = $student && $correct && strtolower($student) === strtolower($correct);

        return [
            'is_correct'    => $isCorrect,
            'score_awarded' => $isCorrect ? (int)$q->score : 0,
        ];
    }

    /**
     * True/False:
     * - correct_tf در جدول questions
     * - پاسخ دانش‌آموز boolean یا "true/false"
     */
    protected function gradeTrueFalse(Question $q, $ans): array
    {
        $student = $this->toBool($ans);
        $correct = (bool)$q->correct_tf;

        $isCorrect = $student === $correct;

        return [
            'is_correct'    => $isCorrect,
            'score_awarded' => $isCorrect ? (int)$q->score : 0,
        ];
    }

    /**
     * Fill Blank:
     * - correct_answer json array در questions
     * - پاسخ دانش‌آموز می‌تواند string یا array باشد
     * - مقایسه بدون حساسیت به حروف/فاصله
     */
    protected function gradeFillBlank(Question $q, $ans): array
    {
        $correctAnswers = collect((array)$q->correct_answer)
            ->map(fn($v) => $this->norm($v))
            ->filter()
            ->values();

        $studentAnswers = collect(is_array($ans) ? $ans : preg_split('/,|\\n/u', (string)$ans))
            ->map(fn($v) => $this->norm($v))
            ->filter()
            ->values();

        // اگر هر جواب صحیح داخل جواب‌های دانش‌آموز بود → درست
        // (برای حالت چند پاسخ)
        $isCorrect = $correctAnswers->count() > 0
            && $correctAnswers->diff($studentAnswers)->isEmpty();

        return [
            'is_correct'    => $isCorrect,
            'score_awarded' => $isCorrect ? (int)$q->score : 0,
        ];
    }

    /** کمک: نرمال‌سازی متن */
    protected function norm(?string $v): string
    {
        $v = trim((string)$v);
        $v = mb_strtolower($v);
        $v = preg_replace('/\s+/u', ' ', $v);
        return $v ?? '';
    }

    /** کمک: تبدیل انواع ورودی به boolean */
    protected function toBool($v): bool
    {
        if (is_bool($v)) return $v;

        $v = strtolower(trim((string)$v));
        return in_array($v, ['1','true','yes','on','صحیح'], true);
    }
}
