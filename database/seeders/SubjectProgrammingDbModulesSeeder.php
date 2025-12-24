<?php

namespace Database\Seeders;

use App\Models\Competency;
use App\Models\Module;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectProgrammingDbModulesSeeder extends Seeder
{
    public function run(): void
    {
        // نام درس طبق فایل شما
        $subject = Subject::query()
            ->where('title_fa', 'توسعه برنامه‌سازی و پایگاه داده')
            ->first();

        if (! $subject) {
            throw new \RuntimeException('Subject not found: توسعه برنامه‌سازی و پایگاه داده');
        }

        $structure = [
            [
                'title' => 'پودمان اول: پیاده‌سازی پایگاه داده',
                'competencies' => [
                    'واحد یادگیری ۱: شایستگی ایجاد پایگاه داده',
                    'واحد یادگیری ۲: شایستگی توسعه پایگاه داده',
                ],
            ],
            [
                'title' => 'پودمان دوم: مدیریت مجموعه داده',
                'competencies' => [
                    'واحد یادگیری ۳: شایستگی کار با ساختار تکرار',
                    'واحد یادگیری ۴: شایستگی کار با آرایه',
                ],
            ],
            [
                'title' => 'پودمان سوم: طراحی واسط گرافیکی',
                'competencies' => [
                    'واحد یادگیری ۵: شایستگی ایجاد واسط گرافیکی کاربر',
                    'واحد یادگیری ۶: شایستگی کار با کنترل‌های پیشرفته',
                ],
            ],
            [
                'title' => 'پودمان چهارم: توسعه واسط گرافیکی',
                'competencies' => [
                    'واحد یادگیری ۷: شایستگی کار با مارس و منو',
                    'واحد یادگیری ۸: شایستگی کار با صفحه‌کلید',
                ],
            ],
            [
                'title' => 'پودمان پنجم: مدیریت پایگاه داده',
                'competencies' => [
                    'واحد یادگیری ۹: شایستگی مدیریت پایگاه داده',
                ],
            ],
        ];

        DB::transaction(function () use ($subject, $structure) {
            foreach ($structure as $mIndex => $m) {
                // جلوگیری از تکراری شدن در اجراهای مجدد
                $module = Module::query()->firstOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'title' => $m['title'],
                    ],
                    [
                        'sort_order' => $mIndex + 1,
                    ]
                );

                foreach ($m['competencies'] as $cIndex => $cTitle) {
                    Competency::query()->firstOrCreate(
                        [
                            'module_id' => $module->id,
                            'title' => $cTitle,
                        ],
                        [
                            'sort_order' => $cIndex + 1,
                        ]
                    );
                }
            }
        });
    }
}
