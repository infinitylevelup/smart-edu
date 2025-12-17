<?php

namespace App\Enums;

enum QuestionType: string
{
    // ✅ مقادیر واقعی DB (طبق enum ستون question_type)
    case MCQ         = 'mcq';
    case DESCRIPTIVE = 'descriptive';
    case TRUE_FALSE  = 'true_false';
    case MATCHING    = 'matching';
    case SHORT_ANSWER = 'short_answer';
    case OTHER       = 'other';

    public function labelFa(): string
    {
        return match ($this) {
            self::MCQ          => 'تستی',
            self::TRUE_FALSE   => 'درست / نادرست',
            self::SHORT_ANSWER => 'جای خالی',
            self::DESCRIPTIVE  => 'تشریحی',
            self::MATCHING     => 'وصل کردنی',
            self::OTHER        => 'سایر',
        };
    }

    /**
     * ورودی‌های UI (wizard) را به مقدار DB نرمال می‌کند.
     * UI فعلی شما: mcq, true_false, fill_blank, essay
     */
    public static function fromInput(string $input): self
    {
        $input = trim($input);

        return match ($input) {
            'mcq' => self::MCQ,
            'true_false' => self::TRUE_FALSE,

            // UI: fill_blank  → DB: short_answer
            'fill_blank' => self::SHORT_ANSWER,

            // UI: essay       → DB: descriptive
            'essay' => self::DESCRIPTIVE,

            // اگر مستقیماً مقدار DB ارسال شد
            'descriptive' => self::DESCRIPTIVE,
            'short_answer' => self::SHORT_ANSWER,
            'matching' => self::MATCHING,
            'other' => self::OTHER,

            default => self::OTHER,
        };
    }

    /**
     * برای نمایش در select و UI: مقدار DB را به مقدار UI برمی‌گرداند
     * تا Wizard edit درست selected شود.
     */
    public static function toUi(string|self|null $dbValue): string
    {
        if (!$dbValue) return '';

        $value = $dbValue instanceof self ? $dbValue->value : $dbValue;

        return match ($value) {
            'mcq' => 'mcq',
            'true_false' => 'true_false',
            'short_answer' => 'fill_blank',
            'descriptive' => 'essay',
            default => $value,
        };
    }

}
