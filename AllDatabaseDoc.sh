#!/usr/bin/env bash

# ==========================================
# Smart-Edu Auto Database Documentation
# Output: docs/database-full.md
# Works on Git Bash / WSL / Linux / macOS
# ==========================================

set -u  # (no -e to avoid silent exit)

ROOT_DIR="$(pwd)"
DOCS_DIR="$ROOT_DIR/docs"
OUT_FILE="$DOCS_DIR/database-full.md"

MIG_DIR="$ROOT_DIR/database/migrations"
MODEL_DIR="$ROOT_DIR/app/Models"
SEEDER_DIR="$ROOT_DIR/database/seeders"
FACTORY_DIR="$ROOT_DIR/database/factories"

echo ">> ROOT_DIR: $ROOT_DIR"
echo ">> OUT_FILE: $OUT_FILE"

mkdir -p "$DOCS_DIR"

# Always create/overwrite output file
{
  echo "# Smart-Edu Database Full Documentation"
  echo ""
  echo "Generated automatically by \`AllDatabaseDoc.sh\` on: $(date)"
  echo ""
  echo "---"
  echo ""
} > "$OUT_FILE"

# ------------------------------------------
# 1) Migrations
# ------------------------------------------
{
  echo "## 1) Migrations"
  echo ""

  if [[ -d "$MIG_DIR" ]]; then
    echo "### Migration Files List"
    echo ""
    ls -1 "$MIG_DIR" 2>/dev/null | sort | sed 's/^/- /'
    echo ""

    echo "### Tables Extracted From Migrations (best-effort)"
    echo ""
    grep -R "Schema::create" -n "$MIG_DIR" 2>/dev/null \
      | sed -E "s/.*Schema::create\('([^']+)'.*/- \1/" \
      | sort -u
    echo ""

    echo "### Schema::table Alterations"
    echo ""
    grep -R "Schema::table" -n "$MIG_DIR" 2>/dev/null \
      | sed -E "s/.*Schema::table\('([^']+)'.*/- \1/" \
      | sort -u
    echo ""
  else
    echo "> migrations folder not found."
    echo ""
  fi

  echo "---"
  echo ""
} >> "$OUT_FILE"

# ------------------------------------------
# 2) Models
# ------------------------------------------
{
  echo "## 2) Eloquent Models"
  echo ""

  if [[ -d "$MODEL_DIR" ]]; then
    echo "### Models List"
    echo ""
    find "$MODEL_DIR" -maxdepth 2 -type f -name "*.php" 2>/dev/null \
      | sed "s|$ROOT_DIR/||" \
      | sort \
      | sed 's/^/- /'
    echo ""

    echo "### Relations Detected (best-effort)"
    echo ""

    while IFS= read -r f; do
      rels=$(grep -nE "belongsToMany\(|belongsTo\(|hasMany\(|hasOne\(|morphMany\(|morphTo\(" "$f" 2>/dev/null || true)
      if [[ -n "$rels" ]]; then
        echo "#### $(basename "$f")"
        echo ""
        echo '```php'
        echo "$rels"
        echo '```'
        echo ""
      fi
    done < <(find "$MODEL_DIR" -type f -name "*.php" 2>/dev/null | sort)

  else
    echo "> app/Models folder not found."
    echo ""
  fi

  echo "---"
  echo ""
} >> "$OUT_FILE"

# ------------------------------------------
# 3) Seeders
# ------------------------------------------
{
  echo "## 3) Seeders"
  echo ""

  if [[ -d "$SEEDER_DIR" ]]; then
    echo "### Seeders List"
    echo ""
    find "$SEEDER_DIR" -type f -name "*.php" 2>/dev/null \
      | sed "s|$ROOT_DIR/||" \
      | sort \
      | sed 's/^/- /'
    echo ""

    echo "### Seeder run() Summary (best-effort)"
    echo ""

    while IFS= read -r f; do
      echo "#### $(basename "$f")"
      echo ""
      echo '```php'
      awk '/function run\(/,/\}\s*$/' "$f" | sed -n '1,220p'
      echo '```'
      echo ""
    done < <(find "$SEEDER_DIR" -type f -name "*.php" 2>/dev/null | sort)

  else
    echo "> database/seeders folder not found."
    echo ""
  fi

  echo "---"
  echo ""
} >> "$OUT_FILE"

# ------------------------------------------
# 4) Factories
# ------------------------------------------
{
  echo "## 4) Factories"
  echo ""

  if [[ -d "$FACTORY_DIR" ]]; then
    echo "### Factories List"
    echo ""
    find "$FACTORY_DIR" -type f -name "*.php" 2>/dev/null \
      | sed "s|$ROOT_DIR/||" \
      | sort \
      | sed 's/^/- /'
    echo ""

    echo "### Factory definition() Summary (best-effort)"
    echo ""

    while IFS= read -r f; do
      echo "#### $(basename "$f")"
      echo ""
      echo '```php'
      awk '/function definition\(/,/\}\s*$/' "$f" | sed -n '1,220p'
      echo '```'
      echo ""
    done < <(find "$FACTORY_DIR" -type f -name "*.php" 2>/dev/null | sort)

  else
    echo "> database/factories folder not found."
    echo ""
  fi

  echo "---"
  echo ""
} >> "$OUT_FILE"

# ------------------------------------------
# 5) ERD Hints
# ------------------------------------------
{
  echo "## 5) ERD Hints (From Migrations)"
  echo ""
  echo "Foreign keys detected automatically (best-effort):"
  echo ""

  if [[ -d "$MIG_DIR" ]]; then
    grep -R "foreignId\|references(" -n "$MIG_DIR" 2>/dev/null \
      | sed "s|$ROOT_DIR/||" \
      | sed 's/^/- /'
    echo ""
  else
    echo "> No migrations found for ERD hints."
    echo ""
  fi

} >> "$OUT_FILE"

echo "✅ Documentation generated at: $OUT_FILE"
