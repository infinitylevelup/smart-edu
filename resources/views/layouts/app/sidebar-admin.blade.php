{{-- resources/views/partials/sidebar-admin.blade.php --}}
@php
    $currentRoute = request()->route()?->getName();
    $isTaxonomyActive =
        str_starts_with($currentRoute ?? '', 'admin.sections') ||
        str_starts_with($currentRoute ?? '', 'admin.grades') ||
        str_starts_with($currentRoute ?? '', 'admin.branches') ||
        str_starts_with($currentRoute ?? '', 'admin.fields') ||
        str_starts_with($currentRoute ?? '', 'admin.subfields') ||
        str_starts_with($currentRoute ?? '', 'admin.subject-types') ||
        str_starts_with($currentRoute ?? '', 'admin.subjects');

    $openClass = $isTaxonomyActive ? 'show' : '';
    $activeClass = $isTaxonomyActive ? 'active' : '';
@endphp

<aside class="sidebar-admin bg-white border-start h-100" style="width:100%;">

    <div class="px-3 py-2">

        {{-- ===== Dashboard ===== --}}
        <a href="{{ route('admin.dashboard') }}"
            class="d-flex align-items-center gap-2 py-2 px-2 rounded text-decoration-none
                  {{ $currentRoute === 'admin.dashboard' ? 'bg-light fw-bold' : '' }}">
            <i class="fas fa-home text-primary"></i>
            <span>داشبورد ادمین</span>
        </a>

        <hr class="my-3">

        {{-- ===== Taxonomy Group ===== --}}
        <div class="mb-2">
            <button
                class="btn w-100 text-start d-flex justify-content-between align-items-center px-2 py-2 rounded {{ $activeClass }}"
                data-bs-toggle="collapse" data-bs-target="#taxonomyMenu"
                aria-expanded="{{ $isTaxonomyActive ? 'true' : 'false' }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-sitemap text-success"></i>
                    دسته‌بندی ساختار
                </span>
                <i class="fas fa-chevron-down small"></i>
            </button>

            <div id="taxonomyMenu" class="collapse {{ $openClass }} mt-2">

                <a href="{{ route('admin.sections.index') }}"
                    class="d-block py-2 px-3 rounded text-decoration-none {{ str_starts_with($currentRoute ?? '', 'admin.sections') ? 'bg-light fw-bold' : '' }}">
                    📌 مقاطع (Sections)
                </a>

                <a href="{{ route('admin.grades.index') }}"
                    class="d-block py-2 px-3 rounded text-decoration-none {{ str_starts_with($currentRoute ?? '', 'admin.grades') ? 'bg-light fw-bold' : '' }}">
                    🎓 پایه‌ها (Grades)
                </a>

                <a href="{{ route('admin.branches.index') }}"
                    class="d-block py-2 px-3 rounded text-decoration-none {{ str_starts_with($currentRoute ?? '', 'admin.branches') ? 'bg-light fw-bold' : '' }}">
                    🧭 شاخه‌ها (Branches)
                </a>

                <a href="{{ route('admin.fields.index') }}"
                    class="d-block py-2 px-3 rounded text-decoration-none {{ str_starts_with($currentRoute ?? '', 'admin.fields') ? 'bg-light fw-bold' : '' }}">
                    🏭 زمینه‌ها (Fields)
                </a>

                <a href="{{ route('admin.subfields.index') }}"
                    class="d-block py-2 px-3 rounded text-decoration-none {{ str_starts_with($currentRoute ?? '', 'admin.subfields') ? 'bg-light fw-bold' : '' }}">
                    🔬 زیررشته‌ها (Subfields)
                </a>

                <a href="{{ route('admin.subject-types.index') }}"
                    class="d-block py-2 px-3 rounded text-decoration-none {{ str_starts_with($currentRoute ?? '', 'admin.subject-types') ? 'bg-light fw-bold' : '' }}">
                    🧩 نوع درس (Subject Types)
                </a>

                <a href="{{ route('admin.subjects.index') }}"
                    class="d-block py-2 px-3 rounded text-decoration-none {{ str_starts_with($currentRoute ?? '', 'admin.subjects') ? 'bg-light fw-bold' : '' }}">
                    📚 دروس (Subjects)
                </a>

            </div>
        </div>

        <hr class="my-3">

        {{-- ===== Reports / Profile (اختیاری) ===== --}}
        <a href="#" class="d-flex align-items-center gap-2 py-2 px-2 rounded text-decoration-none">
            <i class="fas fa-chart-line text-warning"></i>
            <span>گزارش‌ها</span>
        </a>

        <a href="#" class="d-flex align-items-center gap-2 py-2 px-2 rounded text-decoration-none">
            <i class="fas fa-user-cog text-secondary"></i>
            <span>پروفایل</span>
        </a>

    </div>
</aside>

{{-- فاصله محتوا از سایدبار --}}
<style>
    .sidebar-admin a:hover {
        background: #f7f7f7;
    }
</style>
