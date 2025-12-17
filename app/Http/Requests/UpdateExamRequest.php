<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        // بررسی مالکیت آزمون
        $exam = $this->route('exam');
        if (!$exam) {
            return false;
        }

        return $exam->teacher_id === auth()->id();
    }

    public function rules(): array
    {
        $exam = $this->route('exam');
        $isClassExam = $exam && in_array($exam->exam_type, ['class_single', 'class_comprehensive']);

        $base = [
            'title'             => ['required', 'string', 'max:250'],
            'description'       => ['nullable', 'string'],
            'duration_minutes'  => ['required', 'integer', 'min:5', 'max:600'], // نام فیلد اصلاح شد

            'start_at'          => ['nullable', 'date'],
            'end_at'            => ['nullable', 'date', 'after_or_equal:start_at'],

            'shuffle_questions' => ['sometimes', 'boolean'],
            'shuffle_options'   => ['sometimes', 'boolean'],
            'ai_assisted'       => ['sometimes', 'boolean'],

            'is_active'         => ['sometimes', 'boolean'],
            'is_published'      => ['sometimes', 'boolean'],
        ];

        if ($isClassExam) {
            // آزمون کلاسی: فقط اجازه تغییر تنظیمات پایه
            return $base;
        }

        // آزمون عمومی: نیاز به فیلدهای taxonomy و subjects
        // در حالت ویرایش، اگر این فیلدها ارسال شوند باید اعتبارسنجی شوند
        return $base + [
            'section_id'       => ['required_with:grade_id', 'integer', 'exists:sections,id'],
            'grade_id'         => ['required_with:branch_id', 'integer', 'exists:grades,id'],
            'branch_id'        => ['required_with:field_id', 'integer', 'exists:branches,id'],
            'field_id'         => ['required_with:subfield_id', 'integer', 'exists:fields,id'],
            'subfield_id'      => ['sometimes', 'integer', 'exists:subfields,id'],
            'subject_type_id'  => ['required_with:subjects', 'integer', 'exists:subject_types,id'],
            'subjects'         => ['required_with:subject_type_id', 'string'], // JSON string از subject IDs
        ];
    }

    protected function prepareForValidation(): void
    {
        // تبدیل فیلدهای بولین
        foreach (['shuffle_questions', 'shuffle_options', 'ai_assisted', 'is_active', 'is_published'] as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => filter_var($this->input($field), FILTER_VALIDATE_BOOL)
                ]);
            }
        }

        // تبدیل duration به duration_minutes برای سازگاری
        if ($this->has('duration') && !$this->has('duration_minutes')) {
            $this->merge([
                'duration_minutes' => $this->input('duration')
            ]);
        }

        // اطمینان از وجود مقادیر برای فیلدهای taxonomy
        $taxonomyFields = ['section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_type_id'];
        foreach ($taxonomyFields as $field) {
            if ($this->has($field) && empty($this->input($field))) {
                $this->merge([$field => null]);
            }
        }
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان آزمون الزامی است.',
            'title.max' => 'عنوان آزمون نمی‌تواند بیشتر از ۲۵۰ کاراکتر باشد.',
            'duration_minutes.required' => 'مدت زمان آزمون الزامی است.',
            'duration_minutes.min' => 'مدت زمان آزمون باید حداقل ۵ دقیقه باشد.',
            'duration_minutes.max' => 'مدت زمان آزمون نمی‌تواند بیشتر از ۶۰۰ دقیقه (۱۰ ساعت) باشد.',
            'end_at.after_or_equal' => 'تاریخ پایان باید بعد از یا برابر تاریخ شروع باشد.',

            // پیام‌های آزمون عمومی
            'section_id.required_with' => 'بخش آموزشی برای انتخاب پایه الزامی است.',
            'grade_id.required_with' => 'پایه برای انتخاب شاخه الزامی است.',
            'branch_id.required_with' => 'شاخه برای انتخاب زمینه الزامی است.',
            'field_id.required_with' => 'زمینه برای انتخاب زیررشته الزامی است.',
            'subject_type_id.required_with' => 'نوع درس برای انتخاب درس‌ها الزامی است.',
            'subjects.required_with' => 'حداقل یک درس باید انتخاب شود.',
        ];
    }

    /**
     * اعتبارسنجی سفارشی برای اطمینان از سازگاری داده‌ها
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exam = $this->route('exam');

            // بررسی تطابق فیلدها با نوع آزمون
            if ($exam) {
                // برای آزمون کلاسی، فیلدهای taxonomy نباید ارسال شوند
                if ($exam->isClassExam && $this->hasAny(['section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_type_id', 'subjects'])) {
                    $validator->errors()->add(
                        'exam_type',
                        'برای آزمون کلاسی، دسته‌بندی آموزشی و درس‌ها از کلاس گرفته می‌شوند و قابل تغییر نیستند.'
                    );
                }

                // برای آزمون عمومی، اگر فیلدهای taxonomy ارسال شده‌اند باید کامل باشند
                if (!$exam->isClassExam && $this->has('grade_id')) {
                    if (!$this->has('subject_type_id')) {
                        $validator->errors()->add(
                            'subject_type_id',
                            'برای به‌روزرسانی آزمون عمومی، نوع درس باید انتخاب شود.'
                        );
                    }

                    if (!$this->has('subjects')) {
                        $validator->errors()->add(
                            'subjects',
                            'برای به‌روزرسانی آزمون عمومی، حداقل یک درس باید انتخاب شود.'
                        );
                    }
                }
            }

            // اعتبارسنجی فرمت subjects (JSON array)
            if ($this->has('subjects')) {
                $subjects = $this->input('subjects');
                $decoded = json_decode($subjects, true);

                if (!is_array($decoded) || empty($decoded)) {
                    $validator->errors()->add(
                        'subjects',
                        'فرمت درس‌ها نامعتبر است. لطفاً دوباره درس‌ها را انتخاب کنید.'
                    );
                }
            }
        });
    }
}
