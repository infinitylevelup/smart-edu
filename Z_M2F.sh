#!/usr/bin/env bash
set -euo pipefail

# -----------------------------
# Z_M2F.sh
# Merge required files into ONE txt file for review
# Run from project root:  bash Z_M2F.sh
# Output: Z_M2F_OUTPUT.txt
# -----------------------------

OUT="Z_M2F_OUTPUT.txt"
ROOT="$(pwd)"

echo "Smart-Edu Merge Export" > "$OUT"
echo "Root: $ROOT" >> "$OUT"
echo "Generated at: $(date)" >> "$OUT"
echo "----------------------------------------" >> "$OUT"
echo "" >> "$OUT"

add_file () {
  local file="$1"
  if [[ -f "$file" ]]; then
    echo "### FILE: $file" >> "$OUT"
    echo '```' >> "$OUT"
    cat "$file" >> "$OUT"
    echo '' >> "$OUT"
    echo '```' >> "$OUT"
    echo "" >> "$OUT"
    echo "----------------------------------------" >> "$OUT"
    echo "" >> "$OUT"
  fi
}

# 1) Migrations
echo ">> Collecting migrations..." >&2
MIG_DIR="database/migrations"
if [[ -d "$MIG_DIR" ]]; then
  # pick any migration with these keywords
  mapfile -t MIGS < <(
    find "$MIG_DIR" -type f -name "*.php" \
    | grep -Ei "subjects|exams|questions|options|choices|attempts|classrooms|learning_paths|analyses|counselor" \
    | sort
  )
else
  MIGS=()
fi

echo "### SECTION: MIGRATIONS" >> "$OUT"
echo "" >> "$OUT"
if [[ "${#MIGS[@]}" -eq 0 ]]; then
  echo "(No matching migrations found under $MIG_DIR)" >> "$OUT"
  echo "" >> "$OUT"
else
  for f in "${MIGS[@]}"; do add_file "$f"; done
fi

# 2) Models
echo ">> Collecting models..." >&2
MODEL_DIR="app/Models"
MODEL_NAMES=("Subject.php" "Exam.php" "Question.php" "Option.php" "Choice.php" "Attempt.php" "Classroom.php" "User.php")
echo "### SECTION: MODELS" >> "$OUT"
echo "" >> "$OUT"

if [[ -d "$MODEL_DIR" ]]; then
  for name in "${MODEL_NAMES[@]}"; do
    # add if exists
    add_file "$MODEL_DIR/$name"
  done

  # also include any counselor-related models if present
  mapfile -t EXTRA_MODELS < <(
    find "$MODEL_DIR" -type f -name "*.php" \
    | grep -Ei "Counselor|LearningPath|Analysis" \
    | sort
  )
  for f in "${EXTRA_MODELS[@]}"; do add_file "$f"; done
else
  echo "(Models directory not found: $MODEL_DIR)" >> "$OUT"
  echo "" >> "$OUT"
fi

# 3) Factories (optional but useful)
echo ">> Collecting factories..." >&2
FACT_DIR="database/factories"
echo "### SECTION: FACTORIES" >> "$OUT"
echo "" >> "$OUT"

if [[ -d "$FACT_DIR" ]]; then
  mapfile -t FACTS < <(
    find "$FACT_DIR" -type f -name "*.php" \
    | grep -Ei "Subject|Exam|Question|Option|Choice|Attempt|Classroom|LearningPath|Analysis" \
    | sort
  )
  if [[ "${#FACTS[@]}" -eq 0 ]]; then
    echo "(No matching factories found under $FACT_DIR)" >> "$OUT"
    echo "" >> "$OUT"
  else
    for f in "${FACTS[@]}"; do add_file "$f"; done
  fi
else
  echo "(Factories directory not found: $FACT_DIR)" >> "$OUT"
  echo "" >> "$OUT"
fi

echo "✅ Done. Output written to: $OUT" >&2
