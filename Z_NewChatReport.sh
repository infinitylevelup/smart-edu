#!/usr/bin/env bash
# Z_NewChatReport.sh (Robust for Laravel 11/12 + Git Bash Windows)
# Output: E:\backup\prj -> /e/backup/prj

set -uo pipefail   # <- -e رو برداشتیم تا وسط کار نخوابه

timestamp() { date +"%Y-%m-%d_%H-%M-%S"; }
say() { printf "\n\033[1;36m==> %s\033[0m\n" "$1"; }
exists() { command -v "$1" >/dev/null 2>&1; }

ROOT="$(pwd)"
TS="$(timestamp)"

BASE_OUT="/e/backup/prj"
OUT_DIR="$BASE_OUT/newchat_report_$TS"
LOG_FILE="$OUT_DIR/_runlog.txt"

mkdir -p "$OUT_DIR"

log() { echo "[$(date +"%H:%M:%S")] $*" | tee -a "$LOG_FILE"; }

if [[ ! -f "$ROOT/artisan" ]]; then
  log "❌ artisan not found. Run from Laravel root."
  exit 1
fi

log "Starting report generation..."
log "Project root: $ROOT"
log "Output dir:   $OUT_DIR"

# -------- 1) Project meta --------
say "Collecting project meta"
{
  echo "# Project Meta"
  echo "Generated at: $TS"
  echo "Root: $ROOT"
  echo
  echo "## Laravel / PHP / Composer"
  if exists php; then php -v | head -n 2; else echo "php not found"; fi
  if exists composer; then composer -V; else echo "composer not found"; fi
  echo
  echo "## Git"
  if exists git && git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
    echo "Branch: $(git branch --show-current)"
    echo "Last commit: $(git log -1 --oneline)"
    echo "Remote: $(git remote -v | head -n 2)"
  else
    echo "Not a git repo or git not found."
  fi
} > "$OUT_DIR/00_project_meta.md" || log "WARN: meta step failed"

# -------- 2) Routes map --------
say "Collecting routes"
if exists php; then
  php artisan route:list > "$OUT_DIR/01_routes_artisan.txt" 2>&1 || log "WARN: artisan route:list failed"
else
  log "WARN: php not found, skipping artisan route:list"
fi

mkdir -p "$OUT_DIR/routes_raw"
cp -a "$ROOT/routes/." "$OUT_DIR/routes_raw/" 2>/dev/null || log "WARN: copying routes_raw failed"

{
  echo "# Routes Quick Summary"
  echo "Source: routes/*.php"
  echo
  grep -RIn --line-number \
    -E "Route::(get|post|put|patch|delete|resource|middleware|group|prefix|name)\b" \
    "$ROOT/routes" \
    2>/dev/null || true
} > "$OUT_DIR/01_routes_quick_summary.md" || log "WARN: routes summary failed"

# -------- 3) Controllers --------
say "Collecting controllers overview"
CTRL_DIR="$ROOT/app/Http/Controllers"
{
  echo "# Controllers Overview"
  echo
  if [[ -d "$CTRL_DIR" ]]; then
    find "$CTRL_DIR" -type f -name "*.php" \
      | sed "s|$ROOT/||" \
      | sort
  else
    echo "Controllers directory not found."
  fi
  echo
  echo "## Controller Methods (public functions)"
  if [[ -d "$CTRL_DIR" ]]; then
    while IFS= read -r file; do
      rel="${file#$ROOT/}"
      echo
      echo "### $rel"
      grep -nE "public function\s+[A-Za-z0-9_]+\s*\(" "$file" || true
    done < <(find "$CTRL_DIR" -type f -name "*.php" | sort)
  fi
} > "$OUT_DIR/02_controllers_overview.md" || log "WARN: controllers overview failed"

# -------- 4) Models + relationships --------
say "Collecting models & relationships"
MODEL_DIR="$ROOT/app/Models"
if [[ ! -d "$MODEL_DIR" ]]; then MODEL_DIR="$ROOT/app"; fi

REL_REGEX="(hasOne|hasMany|belongsTo|belongsToMany|morphOne|morphMany|morphTo|morphToMany|hasManyThrough)\s*\("

{
  echo "# Models & Relationships"
  echo
  if [[ -d "$MODEL_DIR" ]]; then
    find "$MODEL_DIR" -maxdepth 1 -type f -name "*.php" \
      | sed "s|$ROOT/||" \
      | sort
  else
    echo "Models directory not found."
  fi

  echo
  echo "## Relationships found"
  if [[ -d "$MODEL_DIR" ]]; then
    while IFS= read -r file; do
      rel="${file#$ROOT/}"
      hits=$(grep -nE "$REL_REGEX" "$file" || true)
      if [[ -n "$hits" ]]; then
        echo
        echo "### $rel"
        echo "$hits"
      fi
    done < <(find "$MODEL_DIR" -maxdepth 1 -type f -name "*.php" | sort)
  fi
} > "$OUT_DIR/03_models_relationships.md" || log "WARN: models scan failed"

# -------- 5) Middleware (Laravel 11/12+) --------
say "Collecting middleware"
MW_DIR="$ROOT/app/Http/Middleware"
BOOT_APP="$ROOT/bootstrap/app.php"

{
  echo "# Middleware"
  echo
  if [[ -d "$MW_DIR" ]]; then
    echo "## Middleware classes"
    find "$MW_DIR" -type f -name "*.php" \
      | sed "s|$ROOT/||" \
      | sort
  else
    echo "Middleware directory not found."
  fi

  echo
  echo "## Middleware registration (bootstrap/app.php)"
  if [[ -f "$BOOT_APP" ]]; then
    grep -nE "withMiddleware|alias\(|group\(|middleware\(" "$BOOT_APP" || true
  else
    echo "bootstrap/app.php not found."
  fi

  echo
  echo "## Middleware usage in routes"
  grep -RInE "middleware\(" "$ROOT/routes" 2>/dev/null || true
} > "$OUT_DIR/04_middleware.md" || log "WARN: middleware scan failed"

# -------- 6) Views tree + layouts --------
say "Collecting views tree"
VIEWS_DIR="$ROOT/resources/views"
{
  echo "# Views Tree"
  echo
  if [[ -d "$VIEWS_DIR" ]]; then
    if exists tree; then
      tree -a "$VIEWS_DIR"
    else
      find "$VIEWS_DIR" -type f \
        | sed "s|$VIEWS_DIR/||" \
        | sort
    fi
  else
    echo "Views directory not found."
  fi

  echo
  echo "## Layouts"
  if [[ -d "$VIEWS_DIR/layouts" ]]; then
    find "$VIEWS_DIR/layouts" -type f \
      | sed "s|$ROOT/||" \
      | sort
  else
    echo "layouts folder not found."
  fi
} > "$OUT_DIR/05_views_report.md" || log "WARN: views report failed"

# -------- 7) Migrations summary --------
say "Collecting migrations summary"
MIG_DIR="$ROOT/database/migrations"
{
  echo "# Database Migrations"
  echo
  if [[ -d "$MIG_DIR" ]]; then
    ls -1 "$MIG_DIR" | sort
  else
    echo "Migrations directory not found."
  fi

  echo
  echo "## create table / foreign keys quick scan"
  if [[ -d "$MIG_DIR" ]]; then
    grep -RInE "Schema::create|foreignId|->foreign\(|references\(|onDelete\(|onUpdate\(" "$MIG_DIR" || true
  fi
} > "$OUT_DIR/06_migrations_summary.md" || log "WARN: migrations summary failed"

# -------- 8) Seeders / Factories --------
say "Collecting seeders & factories"
{
  echo "# Seeders"
  if [[ -d "$ROOT/database/seeders" ]]; then
    find "$ROOT/database/seeders" -type f -name "*.php" \
      | sed "s|$ROOT/||" | sort
  else
    echo "Seeders directory not found."
  fi

  echo
  echo "# Factories"
  if [[ -d "$ROOT/database/factories" ]]; then
    find "$ROOT/database/factories" -type f -name "*.php" \
      | sed "s|$ROOT/||" | sort
  else
    echo "Factories directory not found."
  fi
} > "$OUT_DIR/07_seeders_factories.md" || log "WARN: seeders/factories scan failed"

# -------- 9) TODO scan --------
say "Scanning TODO / FIXME / BUG markers"
{
  echo "# TODO / FIXME / BUG scan"
  echo
  grep -RInE "TODO|FIXME|BUG|HACK|NOTE:" \
    "$ROOT/app" "$ROOT/resources" "$ROOT/routes" "$ROOT/database" \
    2>/dev/null || true
} > "$OUT_DIR/08_todo_known_issues.md" || log "WARN: todo scan failed"

# -------- 10) Snapshots --------
say "Saving composer.json / .env.example snapshot"
cp "$ROOT/composer.json" "$OUT_DIR/composer.json" 2>/dev/null || log "WARN: composer.json copy failed"
cp "$ROOT/.env.example" "$OUT_DIR/.env.example" 2>/dev/null || log "WARN: .env.example copy failed"

# -------- Final index (GUARANTEED) --------
say "Writing index file"
{
  echo "# New Chat Report Index"
  echo
  echo "Report folder: $OUT_DIR"
  echo
  echo "## Files"
  ls -1 "$OUT_DIR" | sort || true
  echo
  echo "## How to use in new chat"
  echo "- 01_routes_artisan.txt + 01_routes_quick_summary.md: routes map."
  echo "- 02_controllers_overview.md: controllers/methods."
  echo "- 03_models_relationships.md: models & Eloquent relations."
  echo "- 04_middleware.md: middleware registration & usage."
  echo "- 05_views_report.md: views/layout tree."
  echo "- 06_migrations_summary.md + 07_seeders_factories.md: DB reconstruction."
  echo "- 08_todo_known_issues.md: remaining tasks."
  echo
  echo "Log file: $LOG_FILE"
} > "$OUT_DIR/README_INDEX.md"

log "✅ Done. README_INDEX.md created."
echo "$OUT_DIR"
