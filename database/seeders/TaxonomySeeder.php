<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table("subjects")->truncate();
        DB::table("subject_types")->truncate();
        DB::table("subfields")->truncate();
        DB::table("fields")->truncate();
        DB::table("branches")->truncate();
        DB::table("grades")->truncate();
        DB::table("sections")->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::transaction(function () {

            $insBasic = function ($table, $data) {
                return DB::table($table)->insertGetId(array_merge([
                    "uuid"       => (string) Str::uuid(),
                    "sort_order" => 0,
                    "is_active"  => 1,
                    "created_at" => now(),
                    "updated_at" => now(),
                ], $data));
            };

            // ==========================================================
            // 1) Sections
            // ==========================================================
            $sections = [
                ["name_fa" => "متوسطه اول",  "slug" => "middle-1"],
                ["name_fa" => "متوسطه دوم",  "slug" => "middle-2"],
                ["name_fa" => "هنرستان فنی و حرفه‌ای", "slug" => "tveta"],
                ["name_fa" => "هنرستان کار و دانش",    "slug" => "kar-danesh"],
            ];

            $sectionIds = [];
            $sort = 1;
            foreach ($sections as $s) {
                $sectionIds[$s["name_fa"]] = $insBasic("sections", [
                    "name_fa"    => $s["name_fa"],
                    "slug"       => $s["slug"],
                    "sort_order" => $sort++,
                ]);
            }

            // ==========================================================
            // 2) Grades
            // ==========================================================
            $gradesData = [
                "متوسطه اول" => [
                    ["value"=>"7","name_fa"=>"هفتم"],
                    ["value"=>"8","name_fa"=>"هشتم"],
                    ["value"=>"9","name_fa"=>"نهم"],
                ],
                "متوسطه دوم" => [
                    ["value"=>"10","name_fa"=>"دهم"],
                    ["value"=>"11","name_fa"=>"یازدهم"],
                    ["value"=>"12","name_fa"=>"دوازدهم"],
                ],
                "هنرستان فنی و حرفه‌ای" => [
                    ["value"=>"10","name_fa"=>"دهم"],
                    ["value"=>"11","name_fa"=>"یازدهم"],
                    ["value"=>"12","name_fa"=>"دوازدهم"],
                ],
                "هنرستان کار و دانش" => [
                    ["value"=>"10","name_fa"=>"دهم"],
                    ["value"=>"11","name_fa"=>"یازدهم"],
                    ["value"=>"12","name_fa"=>"دوازدهم"],
                ],
            ];

            $gradeIdsBySection = [];
            $gradeSectionOf = [];
            foreach ($gradesData as $sectionName => $grades) {
                $sort = 1;
                foreach ($grades as $g) {
                    $gid = $insBasic("grades", [
                        "section_id" => $sectionIds[$sectionName],
                        "value"      => $g["value"],
                        "name_fa"    => $g["name_fa"],
                        "slug"       => $sectionName . "-" . $g["value"],
                        "sort_order" => $sort++,
                    ]);
                    $gradeIdsBySection[$sectionName][$g["value"]] = $gid;
                    $gradeSectionOf[$gid] = $sectionIds[$sectionName];
                }
            }

            // ==========================================================
            // 3) Branches
            // ==========================================================
            $branchesData = [
                "متوسطه اول" => ["عمومی"],
                "متوسطه دوم" => ["ریاضی", "تجربی", "انسانی"],
                "هنرستان فنی و حرفه‌ای" => ["فنی‌حرفه‌ای"],
                "هنرستان کار و دانش"    => ["کارودانش"],
            ];

            $branchIds = [];
            $branchSectionOf = [];
            foreach ($branchesData as $sectionName => $branches) {
                $sort = 1;
                foreach ($branches as $b) {
                    $bid = $insBasic("branches", [
                        "section_id" => $sectionIds[$sectionName],
                        "slug"       => Str::slug($b),
                        "name_fa"    => $b,
                        "sort_order" => $sort++,
                    ]);
                    $branchIds[$sectionName][$b] = $bid;
                    $branchSectionOf[$bid] = $sectionIds[$sectionName];
                }
            }

            // ==========================================================
            // 4) Fields
            // ==========================================================
            $fieldsData = [
                "عمومی" => ["عمومی"],

                "ریاضی"  => ["ریاضی‌فیزیک"],
                "تجربی"  => ["علوم تجربی"],
                "انسانی" => ["علوم انسانی"],

                "فنی‌حرفه‌ای" => [
                    "کامپیوتر",
                    "برق",
                    "الکترونیک",
                    "مکانیک خودرو",
                    "حسابداری",
                    "گرافیک",
                    "شبکه و نرم‌افزار",
                ],

                "کارودانش" => [
                    "کامپیوتر (کارودانش)",
                    "تربیت بدنی",
                    "هنر",
                    "حسابداری (کارودانش)",
                ],
            ];

            $fieldIds = [];
            $fieldBranchOf = [];
            foreach ($branchIds as $sectionName => $bMap) {
                foreach ($bMap as $branchName => $branchId) {

                    $fields = $fieldsData[$branchName] ?? ["عمومی"];
                    $sort = 1;

                    foreach ($fields as $f) {
                        $fid = $insBasic("fields", [
                            "branch_id"  => $branchId,
                            "slug"       => Str::slug($f),
                            "name_fa"    => $f,
                            "sort_order" => $sort++,
                        ]);
                        $fieldIds[$branchName][$f] = $fid;
                        $fieldBranchOf[$fid] = $branchId;
                    }
                }
            }

            // ==========================================================
            // 5) Subfields
            // ==========================================================
            $subfieldsData = [
                "کامپیوتر" => ["شبکه","نرم‌افزار","تولید محتوا"],
                "برق" => ["صنعتی","ساختمان"],
                "الکترونیک" => ["دیجیتال","آنالوگ"],
                "شبکه و نرم‌افزار" => ["شبکه","برنامه‌نویسی"],
                "عمومی" => [],
            ];

            $subfieldIds = [];

            foreach ($fieldIds as $branchName => $fMap) {
                foreach ($fMap as $fieldName => $fid) {

                    $subs = $subfieldsData[$fieldName] ?? [];
                    $sort = 1;

                    foreach ($subs as $sf) {
                        $slug = Str::slug($fieldName) . "-" . Str::slug($sf);

                        $sfid = $insBasic("subfields", [
                            "field_id"   => $fid,
                            "slug"       => $slug,
                            "name_fa"    => $sf,
                            "sort_order" => $sort++,
                        ]);

                        $subfieldIds[$fieldName][$sf] = $sfid;
                    }
                }
            }

            // ==========================================================
            // 6) Subject Types
            // ==========================================================
            $subjectTypes = [
                ["name_fa" => "عمومی",  "slug" => "omomi"],
                ["name_fa" => "تخصصی", "slug" => "takhassosi"],
            ];

            $subjectTypeIds = [];
            $sort = 1;
            foreach ($subjectTypes as $st) {
                $stid = $insBasic("subject_types", [
                    "slug"       => $st["slug"],
                    "name_fa"    => $st["name_fa"],
                    "sort_order" => $sort++,
                ]);
                $subjectTypeIds[$st["name_fa"]] = $stid;
            }

            // ==========================================================
            // 7) Subjects - بخش اصلاح شده
            // ==========================================================
            $subjectsData = [
                "عمومی" => [
                    "عمومی" => ["قرآن","ادبیات فارسی","زبان انگلیسی","دین و زندگی"]
                ],
                "ریاضی‌فیزیک" => [
                    "عمومی"  => ["ادبیات","دین و زندگی","زبان"],
                    "تخصصی"  => ["ریاضی 1","ریاضی 2","فیزیک 1","فیزیک 2","هندسه"]
                ],
                "علوم تجربی" => [
                    "عمومی"  => ["ادبیات","دین و زندگی","زبان"],
                    "تخصصی"  => ["زیست 1","زیست 2","شیمی 1","شیمی 2","فیزیک"]
                ],
                "علوم انسانی" => [
                    "عمومی"  => ["ادبیات","دین و زندگی","زبان"],
                    "تخصصی"  => ["تاریخ","جغرافیا","اقتصاد","منطق","جامعه‌شناسی"]
                ],
                "کامپیوتر" => [
                    "عمومی"  => ["ادبیات","دین و زندگی","زبان"],
                    "تخصصی"  => ["برنامه‌سازی","شبکه‌های کامپیوتری","پایگاه داده","سیستم عامل"]
                ],
                "برق" => [
                    "عمومی"  => ["ادبیات","دین و زندگی"],
                    "تخصصی"  => ["مدارهای الکتریکی","نصب و نگهداری","الکترونیک صنعتی"]
                ],
            ];

            foreach ($fieldIds as $branchName => $fMap) {
                foreach ($fMap as $fieldName => $fid) {

                    $branchId  = $fieldBranchOf[$fid];
                    $sectionId = $branchSectionOf[$branchId];

                    $gradesOfSection = collect($gradeIdsBySection)
                        ->filter(fn($v, $secName) => $sectionIds[$secName] == $sectionId)
                        ->first() ?? [];

                    foreach ($gradesOfSection as $gradeValue => $gradeId) {

                        $typeMap = $subjectsData[$fieldName] ?? $subjectsData["عمومی"];

                        foreach ($typeMap as $typeName => $subs) {

                            $typeId = $subjectTypeIds[$typeName] ?? null;
                            $sort = 1;

                            foreach ($subs as $subj) {

                                $slug = Str::slug($fieldName . "-" . $typeName . "-" . $subj . "-" . $gradeValue);

                                // بخش اصلاح شده: اضافه کردن section_id
                                DB::table("subjects")->insert([
                                    "uuid"            => (string) Str::uuid(),
                                    "title_fa"        => $subj,
                                    "slug"            => $slug,
                                    "sort_order"      => $sort++,
                                    "is_active"       => 1,

                                    "section_id"      => $sectionId, // اضافه شد
                                    "grade_id"        => $gradeId,
                                    "branch_id"       => $branchId,
                                    "field_id"        => $fid,
                                    "subfield_id"     => null,
                                    "subject_type_id" => $typeId,

                                    "created_at"      => now(),
                                    "updated_at"      => now(),
                                ]);
                            }
                        }
                    }
                }
            }

        });
    }
}