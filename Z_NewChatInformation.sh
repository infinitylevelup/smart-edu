#!/usr/bin/env bash
set -euo pipefail

###############################################################################
# Z_NewChatInformation.sh
#
# هدف:
#  گرفتن یک Checkpoint جامع از پروژه Smart-Edu تا این لحظه،
#  مخصوصاً برای ادامه کار در چت جدید.
#
# خروجی:
#  پوشه report_YYYY-MM-DD_HH-MM-SS داخل:
#    e:\backup\prj\checkpoint
#  (در bash معادل /e/backup/prj/checkpoint)
#
# اجرا:
#   bash Z_NewChatInformation.sh
#
# پیش‌نیازها:
#   - bash (Git Bash / WSL)
#   - php artisan
#   - mysql cli (اختیاری)
###############################################################################

PROJECT_ROOT="$(pwd)"
OUT_DIR="/e/backup/prj/checkpoint"

TIMESTAMP="$(date +"%Y-%m-%d_%H-%M-%S")"
REPORT_DIR="${OUT_DIR}/report_${TIMESTAMP}"
mkdir -p "$REPORT_DIR"

log() { echo -e "[$(date +"%H:%M:%S")] $*" | tee -a "${REPORT_DIR}/_run.log"; }

###############################################################################
# 0) خواندن تنظیمات DB از .env (اگر بود)
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
# 1) Snapshot درخت فرانت‌اند و Viewها
###############################################################################
log "Collecting frontend tree ..."
{
  echo "== Smart-Edu Frontend Tree =="
  echo
  if command -v tree >/dev/null 2>&1; then
    tree -a resources/views
    echo
    tree -a public/assets
  else
    find resources/views -type f | sed 's|^| - |'
    echo
    find public/assets -type f | sed 's|^| - |'
  fi
  echo
} > "${REPORT_DIR}/frontend_tree.txt"

###############################################################################
# 2) Snapshot مسیرهای Routes
###############################################################################
log "Copying routes/web.php ..."
if [[ -f routes/web.php ]]; then
  cp routes/web.php "${REPORT_DIR}/web.php"
else
  echo "routes/web.php not found" > "${REPORT_DIR}/web.php"
fi

log "Extracting teacher/student route snippets ..."
{
  echo "== TEACHER ROUTES SNIPPET =="
  nl -ba routes/web.php | grep -nE "teacher\.|/teacher|dashboard/teacher|Question Bank|Teacher" || true
  echo
  echo "== STUDENT ROUTES SNIPPET =="
  nl -ba routes/web.php | grep -nE "student\.|/student|dashboard/student|Student|exams" || true
} > "${REPORT_DIR}/routes_snippets_teacher_student.txt"

log "Running artisan route:list (if possible) ..."
if [[ -f artisan ]]; then
  php artisan route:list > "${REPORT_DIR}/artisan_route_list.txt" 2>/dev/null || \
  echo "route:list failed (env not ready?)" > "${REPORT_DIR}/artisan_route_list.txt"
else
  echo "artisan not found" > "${REPORT_DIR}/artisan_route_list.txt"
fi

###############################################################################
# 3) Snapshot کنترلرها
###############################################################################
log "Listing controllers ..."
{
  echo "== All Controllers =="
  find app/Http/Controllers -type f | sort

  echo
  echo "== Teacher Controllers =="
  find app/Http/Controllers -type f | grep -i "Teacher" || true

  echo
  echo "== Student Controllers =="
  find app/Http/Controllers -type f | grep -i "Student" || true
} > "${REPORT_DIR}/controllers_list.txt"

###############################################################################
# 4) Snapshot مدل‌ها و مایگریشن‌ها
###############################################################################
log "Listing models & migrations ..."
{
  echo "== Models =="
  find app/Models -type f | sort
  echo
  echo "== Migrations =="
  find database/migrations -type f | sort

  echo
  echo "== Relevant to exams/questions/attempts/answers =="
  find app/Models -type f | grep -Ei "Exam|Question|Attempt|Answer|Result" || true
  find database/migrations -type f | grep -Ei "exams|questions|attempts|answers|results" || true
} > "${REPORT_DIR}/models_migrations_list.txt"

###############################################################################
# 5) Snapshot فایل‌های کلیدی که تا اینجا ساختیم
###############################################################################
log "Checking key files status ..."
KEY_FILES=(
  # layouts / landing
  "resources/views/layouts/app.blade.php"
  "resources/views/layouts/guest.blade.php"
  "resources/views/landing/index.blade.php"

  # teacher dashboard / exams / questions
  "app/Http/Controllers/Teacher/TeacherExamController.php"
  "app/Http/Controllers/Teacher/QuestionController.php"
  "resources/views/dashboard/teacher/exams/index.blade.php"
  "resources/views/dashboard/teacher/exams/questions.blade.php"
  "resources/views/dashboard/teacher/exams/question-edit.blade.php"

  # student exams
  "app/Http/Controllers/Student/StudentExamController.php"
  "resources/views/dashboard/student/exams/index.blade.php"
  "resources/views/dashboard/student/exams/show.blade.php"

  # models
  "app/Models/Exam.php"
  "app/Models/Question.php"
  "app/Models/Attempt.php"
)

{
  echo "== Key Files Check =="
  echo
  for f in "${KEY_FILES[@]}"; do
    if [[ -f "$f" ]]; then
      echo "[OK] $f"
    else
      echo "[MISSING] $f"
    fi
  done
  echo
} > "${REPORT_DIR}/key_files_check.txt"

###############################################################################
# 6) Snapshot وضعیت چندنوعی شدن سوال‌ها (بررسی migration + model)
###############################################################################
log "Inspecting multi-type question setup ..."
{
  echo "== Multi-type Questions Check =="

  echo
  echo "-- Migration for adding type/options/correct_answer/explanation --"
  find database/migrations -type f | grep -i "add_type_and_payload_to_questions_table" || \
    echo "NOT FOUND"

  echo
  echo "-- Question model casts/fillable --"
  if [[ -f app/Models/Question.php ]]; then
     grep -nE "fillable|casts|type|options|correct_answer|explanation" app/Models/Question.php || true
  else
     echo "Question.php missing"
  fi
} > "${REPORT_DIR}/multitype_questions_check.txt"

###############################################################################
# 7) Snapshot دیتابیس (اختیاری)
###############################################################################
log "Collecting DB schema (optional) ..."
MYSQL_OK=1
if ! command -v mysql >/dev/null 2>&1; then MYSQL_OK=0; fi
if [[ -z "${DB_DATABASE}" || -z "${DB_USERNAME}" ]]; then MYSQL_OK=0; fi

if [[ $MYSQL_OK -eq 1 ]]; then
  mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    "$DB_DATABASE" -e "SHOW TABLES;" > "${REPORT_DIR}/db_tables.txt" 2>/dev/null || MYSQL_OK=0
fi

if [[ $MYSQL_OK -eq 1 ]]; then
  RELEVANT_TABLES=$(awk 'NR>1{print $1}' "${REPORT_DIR}/db_tables.txt" | \
      grep -Ei "exams|questions|attempts|answers|results|students" || true)

  {
    echo "== Relevant tables describe =="
    echo "$RELEVANT_TABLES"
    echo

    for t in $RELEVANT_TABLES; do
      echo "---- DESCRIBE $t ----"
      mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" \
        "$DB_DATABASE" -e "DESCRIBE \`$t\`;" 2>/dev/null || echo "Failed to describe $t"
      echo
    done
  } > "${REPORT_DIR}/db_schema_relevant.txt"
else
  echo "DB dump skipped (mysql not found or creds missing)." > "${REPORT_DIR}/db_tables.txt"
  echo "DB dump skipped." > "${REPORT_DIR}/db_schema_relevant.txt"
fi

###############################################################################
# 8) SUMMARY نهایی برای چت جدید
###############################################################################
log "Writing SUMMARY ..."
{
  echo "== SMART-EDU CHECKPOINT SUMMARY =="
  echo
  echo "Generated at: $TIMESTAMP"
  echo "Project root: $PROJECT_ROOT"
  echo "Report dir:   $REPORT_DIR"
  echo
  echo "Frontend snapshot: frontend_tree.txt"
  echo "Routes snapshot: web.php + routes_snippets_teacher_student.txt"
  echo "Controllers snapshot: controllers_list.txt"
  echo "Models/Migrations snapshot: models_migrations_list.txt"
  echo "Key files status: key_files_check.txt"
  echo "Multi-type questions check: multitype_questions_check.txt"
  echo "DB schema: db_tables.txt + db_schema_relevant.txt"
  echo
  echo "Current work focus:"
  echo " - Teacher: multi-type question management DONE (create/list/edit/delete)."
  echo " - Student: exams index/start exist; show/submit in progress."
  echo
  echo "Next steps in new chat:"
  echo " 1) Verify attempts table has answers json (or add migration)."
  echo " 2) Add student.exams.show + student.exams.submit routes."
  echo " 3) Implement StudentExamController show/submit."
  echo " 4) Update student exams show blade for mcq/tf/blank/essay."
  echo
} > "${REPORT_DIR}/SUMMARY.txt"

log "DONE. Checkpoint saved to: ${REPORT_DIR}"
echo "Checkpoint saved to: ${REPORT_DIR}"
