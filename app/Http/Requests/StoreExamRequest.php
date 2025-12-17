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
            'exam_type'        => ['required', 'string', Rule::in(['public', 'class_single', 'class_comprehensive'])],
            'title'            => ['required', 'string', 'max:250'],
            'description'      => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:300'],
            'is_active'        => ['sometimes', 'boolean'],
        ];

        if ($isClassExam) {
            $type = (string) $this->input('exam_type');
            $requiredClassroomType = $type === 'class_single' ? 'single' : 'comprehensive';

            return $base + [
                'classroom_id' => [
                    'required',
                    'integer',
                    Rule::exists('classrooms', 'id')
                        ->where('teacher_id', auth()->id())
                        ->where('classroom_type', $requiredClassroomType),
                ],
                'exam_type' => 'required|in:public,class,class_single,class_comprehensive',
            ];
        }

        // ✅ برای آزمون عمومی - validation منعطف‌تر
        return $base + [
            'classroom_id' => ['nullable'],

            // اینها required هستند اما اگر در فرم نباشند باید handle شوند
            'section_id'   => ['required', 'integer', 'exists:sections,id'],
            'grade_id'     => ['required', 'integer', 'exists:grades,id'],
            'branch_id'    => ['required', 'integer', 'exists:branches,id'],
            'field_id'     => ['required', 'integer', 'exists:fields,id'],
            'subfield_id'  => ['required', 'integer', 'exists:subfields,id'],

            'subject_type_id' => ['nullable', 'string'],
            'subjects'        => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // ✅ مطمئن شوید مقادیر خالی تبدیل به null شوند
        $this->merge([
            'section_id'      => $this->input('section_id') ?: null,
            'grade_id'        => $this->input('grade_id') ?: null,
            'branch_id'       => $this->input('branch_id') ?: null,
            'field_id'        => $this->input('field_id') ?: null,
            'subfield_id'     => $this->input('subfield_id') ?: null,
            'subject_type_id' => $this->input('subject_type_id') ?: null,
        ]);

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
