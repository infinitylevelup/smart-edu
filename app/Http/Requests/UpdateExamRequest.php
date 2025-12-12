<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $exam = $this->route('exam');
        $isClassExam = $exam && $exam->exam_type !== 'public';

        $base = [
            'title'        => ['required', 'string', 'max:250'],
            'description'  => ['nullable', 'string'],
            'duration'     => ['required', 'integer', 'min:5', 'max:300'],

            'start_at'     => ['nullable', 'date'],
            'end_at'       => ['nullable', 'date', 'after_or_equal:start_at'],

            'shuffle_questions' => ['sometimes', 'boolean'],
            'shuffle_options'   => ['sometimes', 'boolean'],
            'ai_assisted'       => ['sometimes', 'boolean'],

            'is_active'    => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
        ];

        if ($isClassExam) {
            // کلاس: ساختار ممنوع/بی‌اثر
            return $base;
        }

        // public: فعلاً optional (تا وقتی Wizard Edit پیاده نشده نشکنه)
        // اگر فرستاده شد، controller mirror store(public) می‌کنه.
        return $base + [
            'section_id'      => ['sometimes', 'integer', 'exists:sections,id'],
            'grade_id'        => ['sometimes', 'integer', 'exists:grades,id'],
            'branch_id'       => ['sometimes', 'integer', 'exists:branches,id'],
            'field_id'        => ['sometimes', 'integer', 'exists:fields,id'],
            'subfield_id'     => ['sometimes', 'integer', 'exists:subfields,id'],
            'subject_type_id' => ['sometimes', 'integer', 'exists:subject_types,id'],
            'subjects'        => ['sometimes', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['shuffle_questions','shuffle_options','ai_assisted','is_active','is_published'] as $b) {
            if ($this->has($b)) {
                $this->merge([$b => filter_var($this->input($b), FILTER_VALIDATE_BOOL)]);
            }
        }
    }
}
