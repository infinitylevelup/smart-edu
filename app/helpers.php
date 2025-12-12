<?php

use App\Enums\QuestionType;

if (! function_exists('question_type_label')) {
    function question_type_label(?string $type): string
    {
        return QuestionType::tryFrom($type)?->label() ?? 'نامشخص';
    }
}

if (! function_exists('question_type_badge')) {
    function question_type_badge(?string $type): string
    {
        return QuestionType::tryFrom($type)?->badgeClass() ?? 'bg-secondary';
    }
}
