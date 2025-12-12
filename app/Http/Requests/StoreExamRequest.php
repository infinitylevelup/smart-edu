<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $examType = (string) $this->input('exam_type', 'public');
        $isClassExam = $examType !== 'public';

        $base = [
            'exam_type'   => ['required', 'string', Rule::in(['public', 'class_single', 'class_comprehensive'])],
            'title'       => ['required', 'string', 'max:250'],
            'description' => ['nullable', 'string'],
            'duration'    => ['required', 'integer', 'min:5', 'max:300'],
            'is_active'   => ['sometimes', 'boolean'],
        ];

        if ($isClassExam) {
            // کلاس‌محور: فقط کلاس لازم است
            return $base + [
                'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            ];
        }

        // public exam: taxonomy + subjects required (با پشتیبانی ALL)
        return $base + [
            'classroom_id' => ['nullable'],

            // اینها در DB شما integer هستند، پس integer می‌مانند
            'section_id'  => ['required', 'integer', 'exists:sections,id'],
            'grade_id'    => ['required', 'integer', 'exists:grades,id'],
            'branch_id'   => ['required', 'integer', 'exists:branches,id'],
            'field_id'    => ['required', 'integer', 'exists:fields,id'],
            'subfield_id' => ['required', 'integer', 'exists:subfields,id'],

            // ✅ اگر حالت ALL بود، لازم نیست
            'subject_type_slug' => ['nullable', 'string', Rule::in(['all'])],

            // ✅ UI ممکنه UUID بفرسته، و در حالت ALL خالیه
            'subject_type_id' => [
                Rule::requiredIf(fn () => (string) $this->input('subject_type_slug') !== 'all'),
                'nullable',
                'string',
            ],

            // Wizard ارسال می‌کنه: JSON string از uuidها
            'subjects' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $examType = (string) $this->input('exam_type', 'public');
        $isClassExam = $examType !== 'public';

        // normalize is_active if checkbox
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOL),
            ]);
        }

        // ✅ اگر subject_type_slug=all بود، subject_type_id را عمداً خالی کن
        if (!$isClassExam && (string) $this->input('subject_type_slug') === 'all') {
            $this->merge(['subject_type_id' => null]);
        }

        // برای آزمون کلاسی: فیلدهای ساختاری را از ورودی حذف می‌کنیم
        if ($isClassExam) {
            $input = $this->all();
            foreach ([
                'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id',
                'subject_type_id', 'subject_type_slug', 'subjects',
            ] as $k) {
                unset($input[$k]);
            }
            $this->replace($input);
        }
    }
}
