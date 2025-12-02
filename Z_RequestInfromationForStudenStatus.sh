#!/usr/bin/env bash
set -euo pipefail

###############################################################################
# Z_RequestInfromationForStudenStatus.sh
#
# هدف:
#  جمع‌آوری اطلاعات پروژه برای تحلیل بخش دانش‌آموز/آزمون‌ها:
#   - ساختار فرانت (student/exams)
#   - روت‌ها و کنترلرهای مربوط
#   - مدل‌ها و مایگریشن‌های مربوط
#   - جدول‌های دیتابیس و اسکیمای آن‌ها
#   - تشخیص فایل‌های مهمی که وجود ندارند
#  خروجی در پوشه مقصد ذخیره می‌شود.
#
# اجرا:
#   bash Z_RequestInfromationForStudenStatus.sh
#
# پیش‌نیازها:
#   - bash (Git Bash / WSL)
#   - php + composer + artisan در پروژه
#   - mysql cli در PATH (XAMPP: mysql.exe)
#
# تنظیمات DB:
#   1) از فایل .env می‌خواند، اگر نتوانست:
#   2) از متغیرهای محیطی استفاده می‌کند:
#        DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
#
###############################################################################

PROJECT_ROOT="$(pwd)"

# مقصد ویندوزی: e:\backup\prj\student
# در bash:
OUT_DIR="/e/backup/prj/student"

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
  # خط‌های مربوطه را می‌خوانیم؛ اگر نبود، همان env varها می‌ماند.
  DB_HOST="${DB_HOST:-$(grep -E '^DB_HOST=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r' )}"
  DB_PORT="${DB_PORT:-$(grep -E '^DB_PORT=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r' )}"
  DB_DATABASE="${DB_DATABASE:-$(grep -E '^DB_DATABASE=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r' )}"
  DB_USERNAME="${DB_USERNAME:-$(grep -E '^DB_USERNAME=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r' )}"
  DB_PASSWORD="${DB_PASSWORD:-$(grep -E '^DB_PASSWORD=' "$ENV_FILE" | tail -n1 | cut -d= -f2- | tr -d '\r' )}"
fi

DB_PORT="${DB_PORT:-3306}"

###############################################################################
# 1) خروجی درخت فرانت‌اند student/exams
###############################################################################
log "Collecting frontend tree for student/exams ..."
{
  echo "== Student Exams Frontend Tree =="
  echo
  if command -v tree >/dev/null 2>&1; then
    tree -a "resources/views/dashboard/student/exams" || true
  else
    find "resources/views/dashboard/student/exams" -type f 2>/dev/null | sed 's|^| - |' || true
  fi
  echo
} > "${REPORT_DIR}/frontend_tree_student_exams.txt"

###############################################################################
# 2) استخراج Routeهای مرتبط با student/exams
###############################################################################
log "Extracting student exam routes from routes/web.php ..."
WEB_ROUTES="${PROJECT_ROOT}/routes/web.php"
{
  echo "== Grep student/exams routes =="
  echo
  if [[ -f "$WEB_ROUTES" ]]; then
    nl -ba "$WEB_ROUTES" | grep -nE "student|dashboard/student|student\.exams|/exams" || true
  else
    echo "routes/web.php not found!"
  fi
  echo
} > "${REPORT_DIR}/routes_student_exams_snippet.txt"

###############################################################################
# 3) لیست کامل Routeها با artisan (اگر ممکن بود)
###############################################################################
log "Running artisan route:list (if possible) ..."
if [[ -f "${PROJECT_ROOT}/artisan" ]]; then
  php artisan route:list > "${REPORT_DIR}/artisan_route_list.txt" 2>/dev/null || \
  echo "route:list failed (maybe env not ready)" > "${REPORT_DIR}/artisan_route_list.txt"
else
  echo "artisan not found" > "${REPORT_DIR}/artisan_route_list.txt"
fi

###############################################################################
# 4) پیدا کردن کنترلرهای Student Exam
###############################################################################
log "Searching for Student Exam Controllers ..."
{
  echo "== Candidate Student Exam Controllers =="
  echo
  find "app/Http/Controllers" -type f 2>/dev/null | \
    grep -Ei "Student.*Exam|Exam.*Student|student/exam|exams" || true
  echo
} > "${REPORT_DIR}/student_exam_controllers.txt"

###############################################################################
# 5) پیدا کردن ویوهای Student Exam (show/index/submit)
###############################################################################
log "Searching for Student Exam Views ..."
{
  echo "== Candidate Student Exam Views =="
  echo
  find "resources/views/dashboard/student" -type f 2>/dev/null | \
    grep -Ei "exams|exam|show\.blade|index\.blade" || true
  echo
} > "${REPORT_DIR}/student_exam_views.txt"

###############################################################################
# 6) پیدا کردن مدل‌ها و مایگریشن‌های مرتبط
###############################################################################
log "Searching for models/migrations relevant to exams/questions/attempts/answers ..."
{
  echo "== Models =="
  find "app/Models" -type f 2>/dev/null | grep -Ei "Exam|Question|Attempt|Answer|Result" || true
  echo
  echo "== Migrations =="
  find "database/migrations" -type f 2>/dev/null | \
    grep -Ei "exams|questions|attempts|answers|results|students_exams" || true
  echo
} > "${REPORT_DIR}/models_migrations_relevant.txt"

###############################################################################
# 7) دیتابیس: استخراج جدول‌ها و desc جدول‌های مرتبط
###############################################################################
log "Collecting database schema (tables + desc relevant) ..."

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
  # فایل‌های خروجی
  TABLES_OUT="${REPORT_DIR}/db_tables.txt"
  SCHEMA_OUT="${REPORT_DIR}/db_schema_relevant.txt"

  # لیست همه جدول‌ها
  mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    "$DB_DATABASE" -e "SHOW TABLES;" > "$TABLES_OUT" 2>/dev/null || {
      log "Could not connect to DB. Check credentials."
      MYSQL_OK=0
  }

  if [[ $MYSQL_OK -eq 1 ]]; then
    # جدول‌های مرتبط (الگوی نام)
    RELEVANT_TABLES=$(awk 'NR>1{print $1}' "$TABLES_OUT" | \
      grep -Ei "exams|questions|attempts|answers|results|students" || true)

    {
      echo "== Relevant tables (by name pattern) =="
      echo "$RELEVANT_TABLES"
      echo

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
# 8) بررسی وجود/عدم وجود فایل‌های کلیدی موردنیاز Student Exam Flow
###############################################################################
log "Checking for required-but-missing files (heuristic) ..."

REQUIRED_FILES=(
  "app/Http/Controllers/Student/StudentExamController.php"
  "resources/views/dashboard/student/exams/index.blade.php"
  "resources/views/dashboard/student/exams/show.blade.php"
  "resources/views/dashboard/student/exams/submit.blade.php"
  "app/Models/ExamAttempt.php"
  "app/Models/QuestionAnswer.php"
)

{
  echo "== Required Files Check =="
  echo
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
# 9) خلاصه نهایی برای خودم (AI) و کاربر
###############################################################################
log "Writing summary ..."
{
  echo "== SUMMARY (Student Exam Status Intake) =="
  echo
  echo "Project root: $PROJECT_ROOT"
  echo "Report dir:   $REPORT_DIR"
  echo
  echo "Outputs:"
  echo " - frontend_tree_student_exams.txt"
  echo " - routes_student_exams_snippet.txt"
  echo " - artisan_route_list.txt"
  echo " - student_exam_controllers.txt"
  echo " - student_exam_views.txt"
  echo " - models_migrations_relevant.txt"
  echo " - db_tables.txt"
  echo " - db_schema_relevant.txt"
  echo " - required_files_check.txt"
  echo
  echo "Notes:"
  echo " - Any MISSING files are candidates to be created in next steps."
  echo " - DB dump may be skipped if mysql not available or creds missing."
  echo
} > "${REPORT_DIR}/SUMMARY.txt"

log "DONE. Report saved to: ${REPORT_DIR}"
echo
echo "Report saved to: ${REPORT_DIR}"
