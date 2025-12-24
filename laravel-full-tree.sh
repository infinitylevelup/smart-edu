#!/bin/bash

# File: laravel-full-tree.sh
# Description: نمایش کامل ساختار درختی پروژه لاراول (همه فایل‌ها و پوشه‌ها)
# Usage: ./laravel-full-tree.sh [مسیر پروژه] [عمق]

# تنظیم رنگ‌ها برای نمایش بهتر
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
GRAY='\033[0;37m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# متغیرهای پیش‌فرض
MAX_DEPTH=10
SHOW_HIDDEN=false
EXCLUDE_PATTERNS=()

# تابع نمایش کمک
show_help() {
    echo "استفاده: $0 [آپشن‌ها] [مسیر]"
    echo ""
    echo "آپشن‌ها:"
    echo "  -h, --help         نمایش این راهنما"
    echo "  -a, --all          نمایش فایل‌های مخفی"
    echo "  -d, --depth N      تنظیم عمق نمایش (پیش‌فرض: 10)"
    echo "  -e, --exclude PAT  حذف الگوهای خاص (مثال: *.log)"
    echo "  -s, --simple       نمایش ساده بدون رنگ و آیکون"
    echo ""
    echo "مثال‌ها:"
    echo "  $0                       نمایش ساختار پوشه جاری"
    echo "  $0 /path/to/project      نمایش ساختار پروژه در مسیر مشخص"
    echo "  $0 -d 5                  نمایش با عمق 5"
    echo "  $0 -a                    نمایش فایل‌های مخفی هم"
    echo ""
}

# تابع پارس کردن آرگومان‌ها
parse_args() {
    while [[ $# -gt 0 ]]; do
        case $1 in
            -h|--help)
                show_help
                exit 0
                ;;
            -a|--all)
                SHOW_HIDDEN=true
                shift
                ;;
            -d|--depth)
                if [[ -n "$2" && "$2" =~ ^[0-9]+$ ]]; then
                    MAX_DEPTH=$2
                    shift 2
                else
                    echo "خطا: مقدار عمق نامعتبر است"
                    exit 1
                fi
                ;;
            -e|--exclude)
                if [[ -n "$2" ]]; then
                    EXCLUDE_PATTERNS+=("$2")
                    shift 2
                else
                    echo "خطا: الگوی حذف مشخص نشده"
                    exit 1
                fi
                ;;
            -s|--simple)
                RED=''; GREEN=''; YELLOW=''; BLUE=''; PURPLE=''; CYAN=''; GRAY=''; BOLD=''; NC=''
                shift
                ;;
            -*)
                echo "خطا: آپشن ناشناخته: $1"
                show_help
                exit 1
                ;;
            *)
                if [[ -z "$TARGET_PATH" ]]; then
                    TARGET_PATH="$1"
                fi
                shift
                ;;
        esac
    done
}

# تابع بررسی اینکه آیا فایل/پوشه باید حذف شود
should_exclude() {
    local item_name="$1"
    
    # حذف بر اساس الگوها
    for pattern in "${EXCLUDE_PATTERNS[@]}"; do
        if [[ "$item_name" == $pattern ]] || [[ "$item_name" =~ $pattern ]]; then
            return 0
        fi
    done
    
    return 1
}

# تابع دریافت آیکون مناسب برای نوع فایل
get_icon() {
    local filename="$1"
    local is_dir="$2"
    local is_exec="$3"
    
    if [[ "$is_dir" == "true" ]]; then
        echo "📁"
    elif [[ "$is_exec" == "true" ]]; then
        echo "⚡"
    else
        local ext="${filename##*.}"
        if [[ "$filename" == "$ext" ]]; then
            ext=""
        fi
        
        case "$ext" in
            php)
                echo "🐘"
                ;;
            js|jsx)
                echo "📜"
                ;;
            ts|tsx)
                echo "📘"
                ;;
            vue)
                echo "🟢"
                ;;
            css|scss|sass|less)
                echo "🎨"
                ;;
            blade.php)
                echo "🔪"
                ;;
            html|htm)
                echo "🌐"
                ;;
            json)
                echo "📦"
                ;;
            xml|yml|yaml)
                echo "⚙️"
                ;;
            md|txt)
                echo "📝"
                ;;
            sql)
                echo "🗃️"
                ;;
            env|example)
                echo "🔐"
                ;;
            gitignore|gitattributes)
                echo "🔧"
                ;;
            lock)
                echo "🔒"
                ;;
            jpg|jpeg|png|gif|svg|ico)
                echo "🖼️"
                ;;
            pdf)
                echo "📕"
                ;;
            log)
                echo "📊"
                ;;
            *)
                echo "📄"
                ;;
        esac
    fi
}

# تابع دریافت رنگ مناسب برای نوع فایل
get_color() {
    local filename="$1"
    local is_dir="$2"
    local is_exec="$3"
    
    if [[ "$is_dir" == "true" ]]; then
        echo -e "${BLUE}"
    elif [[ "$is_exec" == "true" ]]; then
        echo -e "${GREEN}"
    else
        local ext="${filename##*.}"
        if [[ "$filename" == "$ext" ]]; then
            ext=""
        fi
        
        case "$ext" in
            php)
                echo -e "${PURPLE}"
                ;;
            js|jsx|ts|tsx)
                echo -e "${YELLOW}"
                ;;
            vue)
                echo -e "${GREEN}"
                ;;
            css|scss|sass|less)
                echo -e "${CYAN}"
                ;;
            blade.php)
                echo -e "${RED}"
                ;;
            json)
                echo -e "${GRAY}"
                ;;
            env)
                echo -e "${RED}"
                ;;
            git*)
                echo -e "${GRAY}"
                ;;
            lock)
                echo -e "${RED}"
                ;;
            md|txt)
                echo -e "${GRAY}"
                ;;
            *)
                echo -e "${NC}"
                ;;
        esac
    fi
}

# تابع نمایش ساختار درختی
show_tree() {
    local indent="$1"
    local path="$2"
    local depth="$3"
    
    # اگر به عمق مجاز رسیدیم، برگرد
    if [[ $depth -gt $MAX_DEPTH ]]; then
        echo "${indent}└── ${GRAY}... (عمق بیشتر از $MAX_DEPTH)${NC}"
        return
    fi
    
    local items=()
    
    # خواندن محتویات پوشه
    if [[ "$SHOW_HIDDEN" == "true" ]]; then
        # نمایش همه فایل‌ها شامل مخفی
        while IFS= read -r item; do
            items+=("$item")
        done < <(find "$path" -maxdepth 1 -name ".*" -o -name "*" | sort | sed 's|.*/||' | grep -v '^$')
    else
        # نمایش فقط فایل‌های غیرمخفی
        while IFS= read -r item; do
            items+=("$item")
        done < <(ls -1 "$path" 2>/dev/null | sort)
    fi
    
    local count=${#items[@]}
    local index=0
    
    for item_name in "${items[@]}"; do
        index=$((index + 1))
        local item_path="$path/$item_name"
        
        # رد کردن خود پوشه جاری و والد
        if [[ "$item_name" == "." || "$item_name" == ".." ]]; then
            continue
        fi
        
        # بررسی حذف بر اساس الگو
        if should_exclude "$item_name"; then
            continue
        fi
        
        # تعیین اینکه آیا آخرین آیتم است
        local is_last=false
        if [[ $index -eq $count ]]; then
            is_last=true
        fi
        
        # تعیین نماد برای خطوط
        local line_symbol="├──"
        local next_indent="│   "
        if [[ "$is_last" == "true" ]]; then
            line_symbol="└──"
            next_indent="    "
        fi
        
        # بررسی نوع فایل
        local is_dir=false
        local is_exec=false
        
        if [[ -d "$item_path" ]]; then
            is_dir=true
        elif [[ -x "$item_path" || "$item_name" == *.sh || "$item_name" == artisan ]]; then
            is_exec=true
        fi
        
        # دریافت آیکون و رنگ
        local icon=$(get_icon "$item_name" "$is_dir" "$is_exec")
        local color=$(get_color "$item_name" "$is_dir" "$is_exec")
        
        # نمایش نام فایل/پوشه
        echo -n "$indent$line_symbol "
        echo -ne "$color$icon $item_name${NC}"
        
        # نمایش اطلاعات اضافی
        if [[ "$is_dir" == "true" ]]; then
            echo -e "${GRAY}/${NC}"
            # نمایش تعداد فایل‌های داخل پوشه
            local item_count=$(find "$item_path" -maxdepth 1 -type f 2>/dev/null | wc -l)
            local dir_count=$(find "$item_path" -maxdepth 1 -type d 2>/dev/null | wc -l)
            dir_count=$((dir_count - 1)) # کم کردن خود پوشه
            echo -e "${indent}$next_indent${GRAY}($item_count فایل, $dir_count پوشه)${NC}"
            
            # بازگشت برای نمایش محتویات پوشه
            show_tree "$indent$next_indent" "$item_path" $((depth + 1))
        else
            # نمایش سایز فایل
            if [[ -f "$item_path" ]]; then
                local size=$(stat -f%z "$item_path" 2>/dev/null || stat -c%s "$item_path" 2>/dev/null)
                if [[ -n "$size" ]]; then
                    if [[ $size -lt 1024 ]]; then
                        echo -e "${GRAY} ($size بایت)${NC}"
                    elif [[ $size -lt 1048576 ]]; then
                        echo -e "${GRAY} ($((size/1024)) کیلوبایت)${NC}"
                    else
                        echo -e "${GRAY} ($((size/1048576)) مگابایت)${NC}"
                    fi
                else
                    echo ""
                fi
            else
                echo ""
            fi
        fi
    done
}

# تابع نمایش خلاصه پروژه
show_summary() {
    echo ""
    echo "================================================"
    echo "  ${BOLD}خلاصه پروژه${NC}"
    echo "================================================"
    
    local total_dirs=$(find "$TARGET_PATH" -type d 2>/dev/null | wc -l | tr -d ' ')
    local total_files=$(find "$TARGET_PATH" -type f 2>/dev/null | wc -l | tr -d ' ')
    
    echo "📁 کل پوشه‌ها: $total_dirs"
    echo "📄 کل فایل‌ها: $total_files"
    
    # شمارش فایل‌ها بر اساس نوع
    echo ""
    echo "${BOLD}توزیع فایل‌ها بر اساس نوع:${NC}"
    
    # فایل‌های PHP
    local php_count=$(find "$TARGET_PATH" -name "*.php" -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "🐘 فایل‌های PHP: $php_count"
    
    # فایل‌های JavaScript
    local js_count=$(find "$TARGET_PATH" \( -name "*.js" -o -name "*.jsx" \) -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "📜 فایل‌های JavaScript: $js_count"
    
    # فایل‌های Blade
    local blade_count=$(find "$TARGET_PATH" -name "*.blade.php" -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "🔪 فایل‌های Blade: $blade_count"
    
    # فایل‌های Vue
    local vue_count=$(find "$TARGET_PATH" -name "*.vue" -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "🟢 فایل‌های Vue: $vue_count"
    
    # فایل‌های CSS
    local css_count=$(find "$TARGET_PATH" \( -name "*.css" -o -name "*.scss" -o -name "*.sass" \) -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "🎨 فایل‌های CSS/Sass: $css_count"
    
    # فایل‌های تصویر
    local image_count=$(find "$TARGET_PATH" \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.svg" \) -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "🖼️ فایل‌های تصویر: $image_count"
    
    echo ""
    echo "${BOLD}پوشه‌های مهم لاراول:${NC}"
    
    local dirs=("app" "bootstrap" "config" "database" "public" "resources" "routes" "storage" "tests" "vendor")
    
    for dir in "${dirs[@]}"; do
        if [[ -d "$TARGET_PATH/$dir" ]]; then
            local dir_files=$(find "$TARGET_PATH/$dir" -type f 2>/dev/null | wc -l | tr -d ' ')
            local dir_subdirs=$(find "$TARGET_PATH/$dir" -type d 2>/dev/null | wc -l | tr -d ' ')
            dir_subdirs=$((dir_subdirs - 1))
            echo "📁 $dir/: $dir_files فایل, $dir_subdirs پوشه"
        else
            echo "❌ $dir/: وجود ندارد"
        fi
    done
}

# تابع نمایش فایل‌های اصلی
show_main_files() {
    echo ""
    echo "================================================"
    echo "  ${BOLD}فایل‌های اصلی پروژه${NC}"
    echo "================================================"
    
    local main_files=(
        ".env" ".env.example" ".gitignore" ".gitattributes"
        "composer.json" "composer.lock" "package.json" "package-lock.json"
        "artisan" "server.php" "webpack.mix.js" "vite.config.js"
        "phpunit.xml" "README.md" "CHANGELOG.md" "LICENSE"
    )
    
    for file in "${main_files[@]}"; do
        if [[ -f "$TARGET_PATH/$file" ]]; then
            local size=$(stat -f%z "$TARGET_PATH/$file" 2>/dev/null || stat -c%s "$TARGET_PATH/$file" 2>/dev/null)
            if [[ $size -lt 1024 ]]; then
                size_display="$size بایت"
            elif [[ $size -lt 1048576 ]]; then
                size_display="$((size/1024)) کیلوبایت"
            else
                size_display="$((size/1048576)) مگابایت"
            fi
            echo "✅ $file ($size_display)"
        else
            echo "❌ $file"
        fi
    done
}

# تابع اصلی
main() {
    # پارس آرگومان‌ها
    parse_args "$@"
    
    # تنظیم مسیر هدف
    if [[ -z "$TARGET_PATH" ]]; then
        TARGET_PATH="."
    fi
    
    # بررسی وجود مسیر
    if [[ ! -d "$TARGET_PATH" ]]; then
        echo "خطا: مسیر '$TARGET_PATH' وجود ندارد!"
        exit 1
    fi
    
    # رفتن به مسیر هدف
    cd "$TARGET_PATH" || exit 1
    
    # نمایش سربرگ
    echo ""
    echo "================================================"
    echo "  ${BOLD}ساختار کامل درختی پروژه لاراول${NC}"
    echo "================================================"
    echo "${CYAN}مسیر:$(pwd)${NC}"
    echo "${CYAN}تاریخ: $(date)${NC}"
    echo "${CYAN}عمق نمایش: $MAX_DEPTH${NC}"
    echo "================================================"
    echo ""
    
    # نمایش ساختار درختی از ریشه
    show_tree "" "." 1
    
    # نمایش خلاصه پروژه
    show_summary
    
    # نمایش فایل‌های اصلی
    show_main_files
    
    echo ""
    echo "================================================"
    echo "  ${BOLD}پایان نمایش ساختار درختی${NC}"
    echo "================================================"
    echo ""
}

# اجرای تابع اصلی
main "$@"