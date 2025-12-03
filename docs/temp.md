1. گزارش وضعیت موجود (Current Status)

نام فایل مستند:
docs/current_status_student_ui_phase1.md

مسیر قرارگیری مستندات:
/docs

اگر پوشه‌ی docs نداری، بسازش. این فایل رو هم داخلش بگذار.

Current Status — Student UI (Phase 1)
A) فایل‌هایی که اصلاح/تکمیل شده‌اند ✅

این فایل‌ها در فاز Public Exams UI Phase 1 با هم اصلاح و هماهنگ شدند و الان باید «منبع واقعیت» باشند:

resources/views/dashboard/student/exams/public.blade.php

لیست آزمون‌های free/public

نمایش وضعیت attempt قبلی

دکمه Start یا Result بر اساس Final Attempt

resources/views/dashboard/student/exams/classroom.blade.php

لیست آزمون‌های کلاسی با فیلتر کلاس

هم‌استایل با public

کنترل Final Attempt

resources/views/dashboard/student/exams/show.blade.php

صفحه شروع / جزئیات آزمون

نمایش اطلاعات، سختی، قوانین

جلوگیری از شروع مجدد در حالت Final Attempt

resources/views/dashboard/student/exams/take.blade.php

UI شرکت در آزمون با تایمر و مسیر سوال‌ها

حالت‌های mcq / true_false / fill_blank / essay

ساید ناوبری، progress، submit نهایی

resources/views/dashboard/student/attempts/result.blade.php

نتیجه آزمون

محاسبه درصد/نمره/درست‌غلط بر اساس AttemptAnswer

پیشنهاد مرحله بعد (next exam suggestion)

بخش مرور غلط‌ها و درست‌ها

B) فایل‌های بک‌اندی که با UI جدید هم‌راستا شده‌اند ✅

(این‌ها هم بر اساس نسخه‌ی نهایی‌ای که فرستادی «وضعیت جاری» محسوب می‌شن.)

app/Http/Controllers/Student/StudentExamController.php

index + publicIndex + classroomIndex

show / take / start / submit (با grading کامل)

result / analysis

normalizeDifficulty + gradeQuestion

ثبت AttemptAnswer و آپدیت Attempt

app/Models/Attempt.php

fillable + casts کامل برای score/percent/legacy JSON

رابطه answers() با AttemptAnswer

helper: isFinal()

app/Models/AttemptAnswer.php

ساختار پاسخ‌ها + is_correct + score_awarded

رابطه‌ها: attempt(), question()

C) فایل‌هایی که هنوز اصلاح نشده‌اند ⏳

طبق لیست رسمیِ خودت، این‌ها هنوز باید مرحله‌به‌مرحله روی «کد فعلی» اصلاح شوند:

resources/views/dashboard/student/classrooms/index.blade.php

resources/views/dashboard/student/classrooms/show.blade.php

resources/views/dashboard/student/classrooms/join-class.blade.php

resources/views/dashboard/student/reports/index.blade.php

resources/views/dashboard/student/support/index.blade.php (و هر فایل دیگر داخل support)

resources/views/dashboard/student/learning-path.blade.php

resources/views/dashboard/student/my-teachers.blade.php

resources/views/dashboard/student/profile.blade.php
