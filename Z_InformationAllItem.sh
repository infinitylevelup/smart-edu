#!/usr/bin/env bash
set -euo pipefail

###############################################################################
# Z_InformationAllItem.sh
#
# هدف:
#  ساخت یک چک‌پوینت کامل از وضعیت پروژه Smart Education System
#  برای اینکه AI و خودت هر زمان یک snapshot دقیق داشته باشید:
#
#  خروجی‌ها:
#   1) ساختار فرانت‌اند (dashboard/teacher + dashboard/student + landing)
#   2) اسنیپت روت‌های مهم از routes/web.php
#   3) لیست کامل روت‌ها با artisan route:list
#   4) لیست کنترلرهای Teacher / Student / Auth
#   5) لیست ویوهای Teacher / Student / Landing
#   6) لیست مدل‌ها و مایگریشن‌های مهم
#   7) لیست جدول‌های DB + DESCRIBE جدول‌های مرتبط
#   8) چک فایل‌های کلیدی لازم و گزارش Missing
#   9) Summary نهایی + لاگ اجرا
#
# اجرا:
#   bash Z_InformationAllItem.sh
#
# پیش‌نیازها:
#   - bash (Git Bash / WSL)
#   - php + composer + artisan
#   - mysql cli در PATH (برای dump اسکیمای DB)
#
# تنظیمات DB:
#   1) از .env می‌خواند
#   2) اگر نبود از env varها:
#      DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
###############################################################################

PROJECT_ROOT="$(pwd)"

# مقصد ویندوزی پیشنهادی:
# مثلا: e:\backup\prj\all
OUT_DIR="/e/backup/prj/all"

TIMESTAMP="$(date +"%Y-%m-%d_%H-%M-%S")"
REPORT_DIR="${OUT_DIR}/report_${TIMESTAMP}"

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
# 1) ساختار فرانت‌اند (Teacher / Student / Landing)
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
  print_tree_or_find "public/assets"
} > "${REPORT_DIR}/frontend_tree_all.txt"

###############################################################################
# 2) اسنیپت روت‌های مهم از routes/web.php
###############################################################################
log "Extracting key routes snippet from routes/web.php ..."

WEB_ROUTES="${PROJECT_ROOT}/routes/web.php"
{
  echo "== Key routes snippet (teacher/student/auth/exams/classes) =="; echo
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
# 3) لیست کامل Routeها با artisan
###############################################################################
log "Running artisan route:list (if possible) ..."
if [[ -f "${PROJECT_ROOT}/artisan" ]]; then
  php artisan route:list > "${REPORT_DIR}/artisan_route_list.txt" 2>/dev/null || \
  echo "route:list failed (maybe env not ready)" > "${REPORT_DIR}/artisan_route_list.txt"
else
  echo "artisan not found" > "${REPORT_DIR}/artisan_route_list.txt"
fi

###############################################################################
# 4) کنترلرها (Teacher / Student / Auth / Landing)
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
# 5) ویوها (Teacher / Student / Landing)
###############################################################################
log "Collecting view files list ..."

{
  echo "== Teacher Views =="; echo
  find "resources/views/dashboard/teacher" -type f 2>/dev/null || true
  echo; echo

  echo "== Student Views =="; echo
  find "resources/views/dashboard/student" -type f 2>/dev/null || true
  echo; echo

  echo "== Landing / Layouts =="; echo
  find "resources/views/landing" -type f 2>/dev/null || true
  find "resources/views/layouts" -type f 2>/dev/null || true
  echo
} > "${REPORT_DIR}/views_list.txt"

###############################################################################
# 6) مدل‌ها و مایگریشن‌های مرتبط
###############################################################################
log "Collecting models & migrations relevant to core modules ..."

{
  echo "== Models =="; echo
  find "app/Models" -type f 2>/dev/null | \
    grep -Ei "User|Classroom|Exam|Question|Attempt|Answer|Result|Report|Level" || true
  echo; echo

  echo "== Migrations =="; echo
  find "database/migrations" -type f 2>/dev/null | \
    grep -Ei "users|classrooms|classroom_student|exams|questions|attempts|answers|results|levels|roles" || true
  echo
} > "${REPORT_DIR}/models_migrations_relevant.txt"

###############################################################################
# 7) دیتابیس: جدول‌ها + desc جدول‌های مرتبط
###############################################################################
log "Collecting database schema (tables + describe relevant) ..."

MYSQL_OK=1
if ! command -v mysql >/dev/null 2>&1; then
  MYSQL_OK=0
  log "mysql cli not found in PATH. Skipping DB dump."
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
      log "Could not connect to DB. Check credentials."
      MYSQL_OK=0
  }

  if [[ $MYSQL_OK -eq 1 ]]; then
    RELEVANT_TABLES=$(awk 'NR>1{print $1}' "$TABLES_OUT" | \
      grep -Ei "users|roles|classrooms|classroom_student|exams|questions|attempts|answers|results|levels|reports" || true)

    {
      echo "== Relevant tables (by name/pattern) =="; echo
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
# 8) بررسی فایل‌های کلیدی مورد نیاز (Teacher + Student Flow)
###############################################################################
log "Checking for required-but-missing files (heuristic) ..."

REQUIRED_FILES=(
  # Teacher core
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
  "resources/views/dashboard/teacher/exams/index.blade.php"
  "resources/views/dashboard/teacher/exams/create.blade.php"
  "resources/views/dashboard/teacher/exams/edit.blade.php"
  "resources/views/dashboard/teacher/exams/questions.blade.php"
  "resources/views/dashboard/teacher/students/index.blade.php"

  # Student core
  "app/Http/Controllers/Student/StudentExamController.php"

  # Student views
  "resources/views/dashboard/student/index.blade.php"
  "resources/views/dashboard/student/exams/index.blade.php"
  "resources/views/dashboard/student/exams/show.blade.php"
  "resources/views/dashboard/student/exams/submit.blade.php"

  # Layouts / landing
  "resources/views/layouts/app.blade.php"
  "resources/views/layouts/guest.blade.php"
  "resources/views/landing/index.blade.php"
  "resources/views/landing/hero.blade.php"
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
# 9) SUMMARY نهایی
###############################################################################
log "Writing summary ..."

{
  echo "== SUMMARY (Project Checkpoint Intake) =="; echo
  echo "Timestamp:   $TIMESTAMP"
  echo "Project root: $PROJECT_ROOT"
  echo "Report dir:   $REPORT_DIR"
  echo
  echo "Outputs:"
  echo " - frontend_tree_all.txt"
  echo " - routes_snippet.txt"
  echo " - artisan_route_list.txt"
  echo " - controllers_list.txt"
  echo " - views_list.txt"
  echo " - models_migrations_relevant.txt"
  echo " - db_tables.txt"
  echo " - db_schema_relevant.txt"
  echo " - required_files_check.txt"
  echo " - _run.log"
  echo
  echo "Notes:"
  echo " - Any [MISSING] file is a candidate to be created or fixed."
  echo " - DB dump may be skipped if mysql is not available or creds missing."
  echo " - Use this snapshot as a reliable checkpoint for continued AI work."
  echo
} > "${REPORT_DIR}/SUMMARY.txt"

log "DONE. Report saved to: ${REPORT_DIR}"
echo
echo "Report saved to: ${REPORT_DIR}"
