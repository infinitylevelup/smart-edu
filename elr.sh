#!/bin/bash

# File: export-laravel-tree.sh
# Description: تولید فایل متنی از ساختار کامل پروژه لاراول برای استفاده در هوش مصنوعی
# Usage: ./export-laravel-tree.sh [مسیر پروژه] [نام فایل خروجی]

# متغیرهای پیش‌فرض
PROJECT_PATH="."
OUTPUT_FILE="laravel_project_structure.txt"
INCLUDE_CONTENT=false
MAX_FILE_SIZE=50000  # حداکثر سایز فایل برای خواندن محتوا (50KB)
EXCLUDE_PATTERNS=("*.log" "*.tmp" "*.cache" "*.zip" "*.tar" "*.gz" ".git/*" "node_modules/*" "vendor/*" "storage/logs/*" "storage/framework/*")

# تابع نمایش کمک
show_help() {
    echo "استفاده: $0 [آپشن‌ها] [مسیر پروژه]"
    echo ""
    echo "آپشن‌ها:"
    echo "  -h, --help           نمایش این راهنما"
    echo "  -o, --output FILE    نام فایل خروجی (پیش‌فرض: laravel_project_structure.txt)"
    echo "  -c, --content        شامل کردن محتوای فایل‌های مهم (محدود)"
    echo "  -s, --simple         فقط ساختار، بدون اطلاعات اضافی"
    echo "  -f, --full-content   شامل کردن محتوای بیشتر فایل‌ها (احتیاط: فایل بزرگ می‌شود)"
    echo ""
    echo "مثال‌ها:"
    echo "  $0                              ذخیره ساختار پروژه جاری"
    echo "  $0 /path/to/project             ذخیره ساختار پروژه مشخص"
    echo "  $0 -o project_tree.txt          ذخیره با نام فایل مشخص"
    echo "  $0 -c                          شامل کردن محتوای فایل‌های مهم"
    echo ""
}

# تابع پارس آرگومان‌ها
parse_args() {
    while [[ $# -gt 0 ]]; do
        case $1 in
            -h|--help)
                show_help
                exit 0
                ;;
            -o|--output)
                if [[ -n "$2" ]]; then
                    OUTPUT_FILE="$2"
                    shift 2
                else
                    echo "خطا: نام فایل خروجی مشخص نشده"
                    exit 1
                fi
                ;;
            -c|--content)
                INCLUDE_CONTENT=true
                shift
                ;;
            -s|--simple)
                INCLUDE_CONTENT=false
                shift
                ;;
            -f|--full-content)
                MAX_FILE_SIZE=200000  # افزایش سایز مجاز
                INCLUDE_CONTENT=true
                shift
                ;;
            -*)
                echo "خطا: آپشن ناشناخته: $1"
                show_help
                exit 1
                ;;
            *)
                if [[ -z "$PROJECT_PATH" ]]; then
                    PROJECT_PATH="$1"
                fi
                shift
                ;;
        esac
    done
}

# تابع بررسی حذف فایل/پوشه
should_exclude() {
    local item_path="$1"
    local item_name="$2"
    
    # حذف پوشه‌های بزرگ و غیرضروری
    case "$item_name" in
        ".git"|"node_modules"|"vendor"|"storage/framework"|"storage/logs")
            return 0
            ;;
    esac
    
    # حذف بر اساس الگو
    for pattern in "${EXCLUDE_PATTERNS[@]}"; do
        if [[ "$item_path" == *"$pattern"* ]] || [[ "$item_name" == $pattern ]]; then
            return 0
        fi
    done
    
    return 1
}

# تابع خواندن بخشی از محتوای فایل
read_file_content() {
    local file_path="$1"
    local max_lines=100
    
    if [[ ! -f "$file_path" ]]; then
        echo "[فایل وجود ندارد]"
        return
    fi
    
    # بررسی سایز فایل
    local file_size=$(stat -f%z "$file_path" 2>/dev/null || stat -c%s "$file_path" 2>/dev/null)
    if [[ $file_size -gt $MAX_FILE_SIZE ]]; then
        echo "[فایل بزرگ است - $((file_size/1024))KB - فقط ابتدای فایل نمایش داده می‌شود]"
        echo "--- ابتدای فایل ---"
        head -n $max_lines "$file_path"
        echo "--- انتهای فایل ---"
    else
        cat "$file_path"
    fi
}

# تابع تولید ساختار درختی
generate_tree_structure() {
    local indent="$1"
    local path="$2"
    local depth="$3"
    
    # عمق حداکثر 8 سطح
    if [[ $depth -gt 8 ]]; then
        echo "${indent}└── [عمق بیشتر...]" >> "$OUTPUT_FILE"
        return
    fi
    
    # خواندن محتویات پوشه و مرتب‌سازی
    local items=()
    while IFS= read -r item; do
        items+=("$item")
    done < <(ls -1A "$path" 2>/dev/null | sort)
    
    local count=${#items[@]}
    local index=0
    
    for item_name in "${items[@]}"; do
        index=$((index + 1))
        local item_path="$path/$item_name"
        
        # بررسی حذف
        if should_exclude "$item_path" "$item_name"; then
            continue
        fi
        
        local is_last=false
        if [[ $index -eq $count ]]; then
            is_last=true
        fi
        
        local line_symbol="├──"
        local next_indent="│   "
        if [[ "$is_last" == "true" ]]; then
            line_symbol="└──"
            next_indent="    "
        fi
        
        # نمایش نام
        if [[ -d "$item_path" ]]; then
            echo "${indent}${line_symbol} 📁 $item_name/" >> "$OUTPUT_FILE"
            
            # نمایش تعداد فایل‌ها در پوشه‌های مهم
            if [[ "$item_name" == "app" || "$item_name" == "resources" || "$item_name" == "database" ]] && [[ $depth -lt 4 ]]; then
                local file_count=$(find "$item_path" -type f 2>/dev/null | wc -l | tr -d ' ')
                echo "${indent}${next_indent}   (تعداد فایل: $file_count)" >> "$OUTPUT_FILE"
            fi
            
            # بازگشت برای پوشه‌های مهم
            if [[ $depth -lt 5 ]] || [[ "$item_name" == "app" || "$item_name" == "resources" || "$item_name" == "config" || "$item_name" == "routes" ]]; then
                generate_tree_structure "${indent}${next_indent}" "$item_path" $((depth + 1))
            else
                echo "${indent}${next_indent}   [محتویات پوشه...]" >> "$OUTPUT_FILE"
            fi
            
        else
            # برای فایل‌ها
            local ext="${item_name##*.}"
            local icon="📄"
            
            case "$ext" in
                php) icon="🐘" ;;
                js|jsx) icon="📜" ;;
                vue) icon="🟢" ;;
                css|scss|sass) icon="🎨" ;;
                blade.php) icon="🔪" ;;
                json) icon="📦" ;;
                md|txt) icon="📝" ;;
                sql) icon="🗃️" ;;
                env) icon="🔐" ;;
                gitignore) icon="🔧" ;;
                lock) icon="🔒" ;;
            esac
            
            echo "${indent}${line_symbol} $icon $item_name" >> "$OUTPUT_FILE"
            
            # شامل کردن محتوای فایل‌های مهم
            if [[ "$INCLUDE_CONTENT" == "true" ]]; then
                case "$item_name" in
                    *.php|*.js|*.vue|*.blade.php|app/*.php|resources/*.js|routes/*.php|config/*.php|*.json|*.env|*.md)
                        if [[ -f "$item_path" ]]; then
                            local file_size=$(stat -f%z "$item_path" 2>/dev/null || stat -c%s "$item_path" 2>/dev/null)
                            if [[ $file_size -lt 100000 ]]; then  # کمتر از 100KB
                                echo "${indent}${next_indent}   --- محتوای فایل ---" >> "$OUTPUT_FILE"
                                echo "${indent}${next_indent}   " >> "$OUTPUT_FILE"
                                
                                # خواندن و اضافه کردن محتوا با indentation
                                while IFS= read -r line; do
                                    echo "${indent}${next_indent}   $line" >> "$OUTPUT_FILE"
                                done < "$item_path"
                                
                                echo "${indent}${next_indent}   " >> "$OUTPUT_FILE"
                                echo "${indent}${next_indent}   --- پایان محتوا ---" >> "$OUTPUT_FILE"
                            else
                                echo "${indent}${next_indent}   [فایل بزرگ - $((file_size/1024))KB]" >> "$OUTPUT_FILE"
                            fi
                        fi
                        ;;
                esac
            fi
        fi
    done
}

# تابع تولید اطلاعات پروژه
generate_project_info() {
    echo "================================================" >> "$OUTPUT_FILE"
    echo "          اطلاعات پروژه لاراول" >> "$OUTPUT_FILE"
    echo "================================================" >> "$OUTPUT_FILE"
    echo "تاریخ تولید: $(date)" >> "$OUTPUT_FILE"
    echo "مسیر پروژه: $(cd "$PROJECT_PATH" && pwd)" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
    
    # خواندن composer.json اگر وجود دارد
    if [[ -f "$PROJECT_PATH/composer.json" ]]; then
        echo "--- اطلاعات Composer ---" >> "$OUTPUT_FILE"
        grep -E '"name"|"description"|"version"' "$PROJECT_PATH/composer.json" | head -3 >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    fi
    
    # خواندن package.json اگر وجود دارد
    if [[ -f "$PROJECT_PATH/package.json" ]]; then
        echo "--- اطلاعات NPM/Package ---" >> "$OUTPUT_FILE"
        grep -E '"name"|"description"|"version"' "$PROJECT_PATH/package.json" | head -3 >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    fi
    
    # خواندن فایل env.example یا .env
    local env_file="$PROJECT_PATH/.env"
    if [[ ! -f "$env_file" ]]; then
        env_file="$PROJECT_PATH/.env.example"
    fi
    
    if [[ -f "$env_file" ]]; then
        echo "--- متغیرهای محیطی مهم ---" >> "$OUTPUT_FILE"
        grep -E "^(APP_|DB_|MAIL_|QUEUE_)" "$env_file" | head -10 >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    fi
}

# تابع تولید خلاصه فایل‌ها
generate_file_summary() {
    echo "================================================" >> "$OUTPUT_FILE"
    echo "          خلاصه فایل‌های پروژه" >> "$OUTPUT_FILE"
    echo "================================================" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
    
    # آمار کلی
    local total_files=$(find "$PROJECT_PATH" -type f 2>/dev/null | wc -l | tr -d ' ')
    local total_dirs=$(find "$PROJECT_PATH" -type d 2>/dev/null | wc -l | tr -d ' ')
    
    echo "📊 آمار کلی:" >> "$OUTPUT_FILE"
    echo "  • کل فایل‌ها: $total_files" >> "$OUTPUT_FILE"
    echo "  • کل پوشه‌ها: $total_dirs" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
    
    # آمار بر اساس نوع فایل
    echo "📁 توزیع فایل‌ها بر اساس نوع:" >> "$OUTPUT_FILE"
    
    local file_types=("*.php" "*.js" "*.vue" "*.blade.php" "*.css" "*.scss" "*.json" "*.md" "*.sql")
    local type_names=("PHP" "JavaScript" "Vue" "Blade" "CSS" "Sass/SCSS" "JSON" "Markdown" "SQL")
    
    for i in "${!file_types[@]}"; do
        local count=$(find "$PROJECT_PATH" -name "${file_types[i]}" -type f 2>/dev/null | wc -l | tr -d ' ')
        if [[ $count -gt 0 ]]; then
            echo "  • ${type_names[i]}: $count فایل" >> "$OUTPUT_FILE"
        fi
    done
    echo "" >> "$OUTPUT_FILE"
    
    # پوشه‌های مهم و تعداد فایل‌هایشان
    echo "📁 پوشه‌های مهم لاراول:" >> "$OUTPUT_FILE"
    local important_dirs=("app" "database" "resources" "routes" "config" "public" "tests")
    
    for dir in "${important_dirs[@]}"; do
        if [[ -d "$PROJECT_PATH/$dir" ]]; then
            local dir_files=$(find "$PROJECT_PATH/$dir" -type f 2>/dev/null | wc -l | tr -d ' ')
            echo "  • $dir/: $dir_files فایل" >> "$OUTPUT_FILE"
        fi
    done
}

# تابع تولید محتوای فایل‌های اصلی
generate_key_files_content() {
    if [[ "$INCLUDE_CONTENT" != "true" ]]; then
        return
    fi
    
    echo "" >> "$OUTPUT_FILE"
    echo "================================================" >> "$OUTPUT_FILE"
    echo "          محتوای فایل‌های کلیدی" >> "$OUTPUT_FILE"
    echo "================================================" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
    
    # لیست فایل‌های مهم برای نمایش محتوا
    local key_files=(
        "composer.json"
        "package.json"
        ".env.example"
        "routes/web.php"
        "routes/api.php"
        "app/Http/Controllers/Controller.php"
        "app/Models/User.php"
        "database/migrations/*.php"
        "resources/views/welcome.blade.php"
        "config/app.php"
    )
    
    for file_pattern in "${key_files[@]}"; do
        # اگر الگو باشد (مثل *.php)
        if [[ "$file_pattern" == *"*"* ]]; then
            local files=$(find "$PROJECT_PATH" -path "$file_pattern" -type f 2>/dev/null | head -3)
            for file in $files; do
                local relative_path="${file#$PROJECT_PATH/}"
                echo "📄 فایل: $relative_path" >> "$OUTPUT_FILE"
                echo "---" >> "$OUTPUT_FILE"
                head -50 "$file" >> "$OUTPUT_FILE"  # فقط 50 خط اول
                echo "" >> "$OUTPUT_FILE"
                echo "--- پایان فایل ---" >> "$OUTPUT_FILE"
                echo "" >> "$OUTPUT_FILE"
            done
        else
            local file="$PROJECT_PATH/$file_pattern"
            if [[ -f "$file" ]]; then
                echo "📄 فایل: $file_pattern" >> "$OUTPUT_FILE"
                echo "---" >> "$OUTPUT_FILE"
                head -50 "$file" >> "$OUTPUT_FILE"  # فقط 50 خط اول
                echo "" >> "$OUTPUT_FILE"
                echo "--- پایان فایل ---" >> "$OUTPUT_FILE"
                echo "" >> "$OUTPUT_FILE"
            fi
        fi
    done
}

# تابع اصلی
main() {
    # پارس آرگومان‌ها
    parse_args "$@"
    
    # بررسی وجود مسیر
    if [[ ! -d "$PROJECT_PATH" ]]; then
        echo "خطا: مسیر '$PROJECT_PATH' وجود ندارد!"
        exit 1
    fi
    
    # رفتن به مسیر پروژه
    cd "$PROJECT_PATH" || exit 1
    
    # اطلاع به کاربر
    echo "🔍 در حال اسکن پروژه لاراول..."
    echo "💾 در حال ذخیره در فایل: $OUTPUT_FILE"
    echo "⏳ لطفا صبر کنید..."
    
    # پاک کردن فایل خروجی قبلی
    > "$OUTPUT_FILE"
    
    # شروع تولید فایل
    {
        echo "ساختار پروژه لاراول - برای استفاده در هوش مصنوعی"
        echo "================================================"
        echo ""
        
        # تولید اطلاعات پروژه
        generate_project_info
        
        # تولید ساختار درختی
        echo "================================================" >> "$OUTPUT_FILE"
        echo "          ساختار درختی پروژه" >> "$OUTPUT_FILE"
        echo "================================================" >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
        
        generate_tree_structure "" "." 1
        
        # تولید خلاصه
        generate_file_summary
        
        # تولید محتوای فایل‌های کلیدی
        generate_key_files_content
        
        # پاورقی
        echo "" >> "$OUTPUT_FILE"
        echo "================================================" >> "$OUTPUT_FILE"
        echo "پایان فایل ساختار پروژه" >> "$OUTPUT_FILE"
        echo "تاریخ: $(date)" >> "$OUTPUT_FILE"
        echo "================================================" >> "$OUTPUT_FILE"
        
    } >> "$OUTPUT_FILE"
    
    # نمایش خلاصه
    echo ""
    echo "✅ فایل با موفقیت تولید شد: $OUTPUT_FILE"
    
    local file_size=$(stat -f%z "$OUTPUT_FILE" 2>/dev/null || stat -c%s "$OUTPUT_FILE" 2>/dev/null)
    local line_count=$(wc -l < "$OUTPUT_FILE")
    
    echo "📊 اطلاعات فایل:"
    echo "  • سایز: $((file_size/1024)) کیلوبایت"
    echo "  • تعداد خطوط: $line_count"
    echo ""
    echo "📋 برای استفاده در هوش مصنوعی، می‌توانید:"
    echo "  1. مستقیماً فایل را آپلود کنید"
    echo "  2. یا محتوای آن را کپی کنید"
    echo "  3. یا از آن به عنوان مرجع ساختار پروژه استفاده کنید"
    
    # نمایش ابتدای فایل برای اطمینان
    echo ""
    echo "🔍 نمایش ابتدای فایل خروجی:"
    echo "---"
    head -20 "$OUTPUT_FILE"
    echo "..."
    echo "---"
}

# اجرای تابع اصلی
main "$@"