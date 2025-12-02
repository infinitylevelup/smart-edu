#!/usr/bin/env bash
# FrontEndTree.sh
# نمایش کوتاه ساختار فرانت‌اند از ریشه پروژه Laravel

set -e

ROOT="${1:-.}"

echo "== Smart-Edu Frontend Tree (root: $ROOT) =="
echo

print_section () {
  local label="$1"
  local path="$2"
  if [ -e "$ROOT/$path" ]; then
    echo "-- $label: $path"
    if command -v tree >/dev/null 2>&1; then
      tree -a -L 4 "$ROOT/$path"
    else
      find "$ROOT/$path" -maxdepth 4 -print | sed "s|^$ROOT/||"
    fi
    echo
  fi
}

print_section "Views (Blade)" "resources/views"
print_section "Public assets" "public"
print_section "Frontend source (CSS/JS)" "resources"
print_section "Build config" "vite.config.js"
print_section "NPM config" "package.json"

echo "== End =="
