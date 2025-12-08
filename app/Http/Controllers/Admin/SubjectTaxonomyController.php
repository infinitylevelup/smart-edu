<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Subfield;

class SubjectTaxonomyController extends Controller
{
    /**
     * ✅ پایه‌ها بر اساس مقطع
     * GET /dashboard/admin/api/grades/by-section/{section}
     */
    public function gradesBySection(string $sectionId)
    {
        $grades = Grade::query()
            ->where('section_id', $sectionId)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'section_id']);

        return response()->json($grades);
    }

    /**
     * ✅ شاخه‌ها بر اساس مقطع
     * GET /dashboard/admin/api/branches/by-section/{section}
     */
    public function branchesBySection(string $sectionId)
    {
        $branches = Branch::query()
            ->where('section_id', $sectionId)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'section_id']);

        return response()->json($branches);
    }

    /**
     * ✅ زمینه‌ها بر اساس شاخه
     * GET /dashboard/admin/api/fields/by-branch/{branch}
     */
    public function fieldsByBranch(string $branchId)
    {
        $fields = Field::query()
            ->where('branch_id', $branchId)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'branch_id']);

        return response()->json($fields);
    }

    /**
     * ✅ زیررشته‌ها بر اساس زمینه
     * GET /dashboard/admin/api/subfields/by-field/{field}
     */
    public function subfieldsByField(string $fieldId)
    {
        $subfields = Subfield::query()
            ->where('field_id', $fieldId)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'field_id']);

        return response()->json($subfields);
    }
}
