#!/usr/bin/env bash
set -euo pipefail

###############################################################################
# Z_ReportCreate.sh
#
# هدف:
#  ساخت چک‌پوینت جامع از پروژه Smart Education System
#  + ذخیره‌ی خلاصه مهم همین چت برای ادامه در جلسات بعدی
#
# خروجی‌ها:
#  1) frontend_tree_all.txt      درخت کامل ویوها و assets
#  2) routes_snippet.txt        اسنیپت روت‌های مهم از routes/web.php
#  3) artisan_route_list.txt    خروجی php artisan route:list
#  4) controllers_list.txt      لیست کنترلرهای Teacher/Student/Auth
#  5) views_list.txt            لیست ویوهای Teacher/Student/Landing
#  6) models_migrations.txt     مدل‌ها و مایگریشن‌های مرتبط
#  7) db_tables.txt             لیست جدول‌های DB
#  8) db_schema_relevant.txt    DESCRIBE جدول‌های مرتبط
#  9) required_files_check.txt  چک فایل‌های ضروری + missing
# 10) CHAT_CHECKPOINT.txt       چک‌پوینت متنی همین چت
# 11) SUMMARY.txt               خلاصه همه خروجی‌ها
# 12) _run.log                  لاگ اجرا
#
# اجرا:
#  bash Z_ReportCreate.sh
###############################################################################

PROJECT_ROOT="$(pwd)"

# مسیر پیشنهادی خروجی (همون استایلی که گفتی)
OUT_DIR="/e/backup/prj/all"

TIMESTAMP="$(date +"%Y-%m-%d_%H-%M-%S")"
REPORT_DIR="${OUT_DIR}/checkpoint_${TIMESTAMP}"

mkdir -p "$REPORT_DIR"

log() { echo -e "[$(date +"%H:%M:%S")] $*" | tee -a "${REPORT_DIR}/_run.log"; }

###############################################################################
# 0) خواندن تنظیمات دیتابیس از .env
###############################################################################
ENV_FILE="${PROJECT_ROOT}/.env"

DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"

if [[ -f "$ENV_FILE" ]]; then
  log "Reading DB config from .env ..."
  DB_HOST="${DB_HOST:-$(grep -E '^DB_HOST=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r')}"
  DB_PORT="${DB_PORT:-$(grep -E '^DB_PORT=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r')}"
  DB_DATABASE="${DB_DATABASE:-$(grep -E '^DB_DATABASE=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r')}"
  DB_USERNAME="${DB_USERNAME:-$(grep -E '^DB_USERNAME=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r')}"
  DB_PASSWORD="${DB_PASSWORD:-$(grep -E '^DB_PASSWORD=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r')}"
fi

DB_PORT="${DB_PORT:-3306}"

###############################################################################
# 1) ساختار فرانت‌اند (Views + public)
###############################################################################
log "Collecting frontend trees ..."

print_tree_or_find() {
  local path="$1"
  echo "== TREE: $path =="; echo
  if command -v tree >/dev/null 2>&1; then
    tree -a "$path" 2>/dev/null || true
  else
    find "$path" -type f 2>/dev/null | sed 's|^| - |' || true
  fi
  echo; echo
}

{
  print_tree_or_find "resources/views/dashboard/teacher"
  print_tree_or_find "resources/views/dashboard/student"
  print_tree_or_find "resources/views/landing"
  print_tree_or_find "resources/views/layouts"
  print_tree_or_find "resources/views/modals"
  print_tree_or_find "resources/views/partials"
  print_tree_or_find "public"
} > "${REPORT_DIR}/frontend_tree_all.txt"

###############################################################################
# 2) اسنیپت روت‌های مهم
###############################################################################
log "Extracting key routes snippet from routes/web.php ..."

WEB_ROUTES="${PROJECT_ROOT}/routes/web.php"
{
  echo "== Key routes snippet (teacher/student/auth/exams/classes/questions) =="; echo
  if [[ -f "$WEB_ROUTES" ]]; then
    nl -ba "$WEB_ROUTES" | grep -nE \
      "dashboard/teacher|dashboard/student|teacher\.|student\.|auth|otp|exams|questions|classes|reports|profile|landing" \
      || true
  else
    echo "routes/web.php not found!"
  fi
  echo
} > "${REPORT_DIR}/routes_snippet.txt"

###############################################################################
# 3) لیست کامل روت‌ها
###############################################################################
log "Running artisan route:list ..."
if [[ -f "${PROJECT_ROOT}/artisan" ]]; then
  php artisan route:list > "${REPORT_DIR}/artisan_route_list.txt" 2>/dev/null || \
  echo "route:list failed (env not ready)" > "${REPORT_DIR}/artisan_route_list.txt"
else
  echo "artisan not found" > "${REPORT_DIR}/artisan_route_list.txt"
fi

###############################################################################
# 4) کنترلرها
###############################################################################
log "Collecting controllers list ..."
{
  echo "== Teacher Controllers =="; echo
  find "app/Http/Controllers/Teacher" -type f 2>/dev/null || true
  echo; echo

  echo "== Student Controllers =="; echo
  find "app/Http/Controllers/Student" -type f 2>/dev/null || true
  echo; echo

  echo "== Auth / Public Controllers =="; echo
  find "app/Http/Controllers" -maxdepth 1 -type f 2>/dev/null || true
  echo
} > "${REPORT_DIR}/controllers_list.txt"

###############################################################################
# 5) ویوها
###############################################################################
log "Collecting view files list ..."
{
  echo "== Teacher Views =="; echo
  find "resources/views/dashboard/teacher" -type f 2>/dev/null || true
  echo; echo

  echo "== Student Views =="; echo
  find "resources/views/dashboard/student" -type f 2>/dev/null || true
  echo; echo

  echo "== Landing / Layouts / Partials / Modals =="; echo
  find "resources/views/landing" -type f 2>/dev/null || true
  find "resources/views/layouts" -type f 2>/dev/null || true
  find "resources/views/partials" -type f 2>/dev/null || true
  find "resources/views/modals" -type f 2>/dev/null || true
  echo
} > "${REPORT_DIR}/views_list.txt"

###############################################################################
# 6) مدل‌ها و مایگریشن‌ها
###############################################################################
log "Collecting models & migrations relevant to core modules ..."
{
  echo "== Models =="; echo
  find "app/Models" -type f 2>/dev/null | \
    grep -Ei "User|Classroom|Exam|Question|Attempt|Answer|Result|Report|Level" || true
  echo; echo

  echo "== Migrations =="; echo
  find "database/migrations" -type f 2>/dev/null | \
    grep -Ei "users|roles|classrooms|classroom_student|exams|questions|attempts|answers|results|levels|reports" || true
  echo
} > "${REPORT_DIR}/models_migrations.txt"

###############################################################################
# 7) دیتابیس (tables + describe)
###############################################################################
log "Collecting database schema (tables + describe relevant) ..."

MYSQL_OK=1
if ! command -v mysql >/dev/null 2>&1; then
  MYSQL_OK=0
  log "mysql cli not found. Skipping DB dump."
fi

if [[ -z "${DB_DATABASE}" || -z "${DB_USERNAME}" ]]; then
  MYSQL_OK=0
  log "DB config incomplete. Skipping DB dump."
fi

if [[ $MYSQL_OK -eq 1 ]]; then
  TABLES_OUT="${REPORT_DIR}/db_tables.txt"
  SCHEMA_OUT="${REPORT_DIR}/db_schema_relevant.txt"

  mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    "$DB_DATABASE" -e "SHOW TABLES;" > "$TABLES_OUT" 2>/dev/null || {
      log "Could not connect to DB. Skipping."
      MYSQL_OK=0
  }

  if [[ $MYSQL_OK -eq 1 ]]; then
    RELEVANT_TABLES=$(awk 'NR>1{print $1}' "$TABLES_OUT" | \
      grep -Ei "users|roles|classrooms|classroom_student|exams|questions|attempts|answers|results|levels|reports" || true)

    {
      echo "== Relevant tables =="; echo
      echo "$RELEVANT_TABLES"; echo

      for t in $RELEVANT_TABLES; do
        echo "---- DESCRIBE $t ----"
        mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" \
          "$DB_DATABASE" -e "DESCRIBE \`$t\`;" 2>/dev/null || echo "Failed to describe $t"
        echo
      done
    } > "$SCHEMA_OUT"
  fi
else
  echo "DB dump skipped." > "${REPORT_DIR}/db_tables.txt"
  echo "DB dump skipped." > "${REPORT_DIR}/db_schema_relevant.txt"
fi

###############################################################################
# 8) Required files check (Teacher/Student core)
###############################################################################
log "Checking required files ..."

REQUIRED_FILES=(
  # Teacher controllers
  "app/Http/Controllers/Teacher/DashboardController.php"
  "app/Http/Controllers/Teacher/TeacherClassController.php"
  "app/Http/Controllers/Teacher/TeacherStudentController.php"
  "app/Http/Controllers/Teacher/TeacherExamController.php"
  "app/Http/Controllers/Teacher/QuestionController.php"

  # Teacher views
  "resources/views/dashboard/teacher/index.blade.php"
  "resources/views/dashboard/teacher/classes/index.blade.php"
  "resources/views/dashboard/teacher/classes/create.blade.php"
  "resources/views/dashboard/teacher/classes/edit.blade.php"
  "resources/views/dashboard/teacher/classes/show.blade.php"
  "resources/views/dashboard/teacher/classes/students.blade.php"
  "resources/views/dashboard/teacher/students/index.blade.php"

  # Student controllers/views
  "app/Http/Controllers/Student/StudentExamController.php"
  "resources/views/dashboard/student/index.blade.php"

  # Layouts / landing
  "resources/views/layouts/app.blade.php"
  "resources/views/layouts/guest.blade.php"
  "resources/views/landing/index.blade.php"
)

{
  echo "== Required Files Check =="; echo
  for f in "${REQUIRED_FILES[@]}"; do
    if [[ -f "$f" ]]; then
      echo "[OK] $f"
    else
      echo "[MISSING] $f"
    fi
  done
  echo
} > "${REPORT_DIR}/required_files_check.txt"

###############################################################################
# 9) چک‌پوینت متنی همین چت
###############################################################################
log "Writing chat checkpoint ..."
cat > "${REPORT_DIR}/CHAT_CHECKPOINT.txt" <<'EOF'
SMART EDU - CHAT CHECKPOINT (Phase 7+)

- Teacher Classes module complete:
  Routes:
    teacher.classes.index/create/store/show/edit/update/destroy
    teacher.classes.students + students.add + students.remove
  Views built with modern UI:
    dashboard/teacher/classes/{index,create,edit,show,students}.blade.php

- Exams linked to classes:
  Added exams.classroom_id migration + relations.
  Exam->classroom() and store sets classroom_id.

- Teacher Students list:
  View:
    dashboard/teacher/students/index.blade.php (table/cards + filters + KPI)
  Controller:
    TeacherStudentController@index
  Fixes:
    User::classrooms() relation added (belongsToMany pivot classroom_student)
    view path fixed to dashboard.teacher.students.index
    duplicate route teacher.teacher.students must be removed in web.php.

- Project stack:
  Laravel 12 + Bootstrap 5 + MySQL on XAMPP/Windows
  Phase 7 views/layouts ongoing.

Next steps:
  A) Teacher-side student profile page
  B) Teacher-side student exam results page
EOF

###############################################################################
# 10) SUMMARY نهایی
###############################################################################
log "Writing summary ..."
{
  echo "== SUMMARY =="; echo
  echo "Timestamp:   $TIMESTAMP"
  echo "Project root: $PROJECT_ROOT"
  echo "Report dir:   $REPORT_DIR"
  echo
  echo "Outputs:"
  ls -1 "$REPORT_DIR"
  echo
} > "${REPORT_DIR}/SUMMARY.txt"

log "DONE. Checkpoint saved to: ${REPORT_DIR}"
echo "Checkpoint saved to: ${REPORT_DIR}"
