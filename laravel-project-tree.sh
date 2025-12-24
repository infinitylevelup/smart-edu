#!/bin/bash

# File: laravel-project-tree.sh
# Description: نمایش درختی ساختار پروژه لاراول (فرانت‌اند و بک‌اند)
# Usage: ./laravel-project-tree.sh [مسیر پروژه]

# تابع نمایش سربرگ
show_header() {
    echo "========================================="
    echo "   ساختار درختی پروژه لاراول   "
    echo "========================================="
    echo "تاریخ: $(date)"
    echo "مسیر: $(pwd)"
    echo "========================================="
    echo ""
}

# تابع نمایش ساختار درختی
show_tree() {
    local indent="$1"
    local path="$2"
    
    # برای هر فایل/پوشه در مسیر داده شده
    for item in "$path"/*; do
        local name=$(basename "$item")
        
        # نادیده گرفتن برخی پوشه‌ها
        if [[ "$name" == "node_modules" || "$name" == "vendor" || "$name" == ".git" || "$name" == ".idea" || "$name" == ".vscode" ]]; then
            continue
        fi
        
        # نمایش نام فایل/پوشه
        echo -n "$indent"
        
        if [[ -d "$item" ]]; then
            # اگر پوشه است
            echo "📁 $name/"
            
            # برای پوشه‌های خاص، اطلاعات بیشتری نمایش دهید
            if [[ "$name" == "app" || "$name" == "resources" || "$name" == "routes" || "$name" == "database" || "$name" == "public" || "$name" == "config" ]]; then
                show_tree "  $indent" "$item"
            fi
        else
            # اگر فایل است
            # نمایش پسوند فایل برای شناسایی نوع
            local ext="${name##*.}"
            if [[ "$name" == "$ext" ]]; then
                ext=""
            fi
            
            case "$ext" in
                "php")
                    echo "📄 $name (PHP)"
                    ;;
                "js"|"jsx")
                    echo "📜 $name (JavaScript)"
                    ;;
                "vue")
                    echo "⚡ $name (Vue.js)"
                    ;;
                "css"|"scss"|"sass")
                    echo "🎨 $name (Styles)"
                    ;;
                "blade.php")
                    echo "🔹 $name (Blade)"
                    ;;
                *)
                    echo "📄 $name"
                    ;;
            esac
        fi
    done
}

# تابع نمایش ساختار فرانت‌اند
show_frontend() {
    echo ""
    echo "======================="
    echo "   بخش فرانت‌اند   "
    echo "======================="
    echo ""
    
    # پوشه resources (مهم‌ترین بخش فرانت‌اند در لاراول)
    if [[ -d "resources" ]]; then
        echo "📁 resources/"
        echo "  📁 js/"
        if [[ -d "resources/js" ]]; then
            for item in resources/js/*; do
                if [[ -f "$item" ]]; then
                    echo "    📜 $(basename $item)"
                elif [[ -d "$item" ]]; then
                    echo "    📁 $(basename $item)/"
                fi
            done
        fi
        
        echo "  📁 css/"
        if [[ -d "resources/css" ]]; then
            for item in resources/css/*; do
                if [[ -f "$item" ]]; then
                    echo "    🎨 $(basename $item)"
                fi
            done
        fi
        
        echo "  📁 views/"
        if [[ -d "resources/views" ]]; then
            for item in resources/views/*; do
                if [[ -d "$item" ]]; then
                    echo "    📁 $(basename $item)/"
                else
                    echo "    🔹 $(basename $item)"
                fi
            done
        fi
    fi
    
    # فایل‌های اصلی فرانت‌اند
    echo ""
    echo "فایل‌های اصلی:"
    if [[ -f "package.json" ]]; then
        echo "  📦 package.json"
    fi
    if [[ -f "webpack.mix.js" ]]; then
        echo "  ⚙️  webpack.mix.js"
    fi
    if [[ -f "vite.config.js" ]]; then
        echo "  ⚡ vite.config.js"
    fi
}

# تابع نمایش ساختار بک‌اند
show_backend() {
    echo ""
    echo "======================"
    echo "   بخش بک‌اند   "
    echo "======================"
    echo ""
    
    # پوشه app (قلب بک‌اند لاراول)
    if [[ -d "app" ]]; then
        echo "📁 app/"
        echo "  📁 Http/"
        if [[ -d "app/Http" ]]; then
            echo "    📁 Controllers/"
            if [[ -d "app/Http/Controllers" ]]; then
                for item in app/Http/Controllers/*; do
                    if [[ -f "$item" ]]; then
                        echo "      📄 $(basename $item)"
                    elif [[ -d "$item" ]]; then
                        echo "      📁 $(basename $item)/"
                    fi
                done
            fi
            
            echo "    📁 Middleware/"
            if [[ -d "app/Http/Middleware" ]]; then
                for item in app/Http/Middleware/*; do
                    if [[ -f "$item" ]]; then
                        echo "      📄 $(basename $item)"
                    fi
                done
            fi
        fi
        
        echo "  📁 Models/"
        if [[ -d "app/Models" ]]; then
            for item in app/Models/*; do
                if [[ -f "$item" ]]; then
                    echo "    📄 $(basename $item)"
                fi
            done
        fi
    fi
    
    # پوشه‌های دیگر بک‌اند
    echo ""
    echo "پوشه‌های مهم بک‌اند:"
    if [[ -d "database" ]]; then
        echo "  📁 database/"
        echo "    📁 migrations/"
        echo "    📁 seeders/"
    fi
    
    if [[ -d "routes" ]]; then
        echo "  📁 routes/"
        for item in routes/*; do
            if [[ -f "$item" ]]; then
                echo "    📄 $(basename $item)"
            fi
        done
    fi
    
    if [[ -d "config" ]]; then
        echo "  📁 config/"
    fi
}

# تابع اصلی
main() {
    # اگر مسیر به عنوان آرگومان داده شده باشد، به آن مسیر بروید
    if [[ -n "$1" ]]; then
        if [[ -d "$1" ]]; then
            cd "$1"
        else
            echo "خطا: مسیر '$1' وجود ندارد!"
            exit 1
        fi
    fi
    
    # بررسی اینکه آیا این یک پروژه لاراول است
    if [[ ! -f "artisan" && ! -f "composer.json" ]]; then
        echo "خطا: این پوشه یک پروژه لاراول به نظر نمی‌رسد!"
        echo "فایل artisan یا composer.json یافت نشد."
        exit 1
    fi
    
    # نمایش سربرگ
    show_header
    
    # نمایش ساختار کلی
    echo "ساختار کلی پروژه:"
    echo ""
    show_tree "" "."
    
    # نمایش بخش فرانت‌اند
    show_frontend
    
    # نمایش بخش بک‌اند
    show_backend
    
    echo ""
    echo "========================================="
    echo "   پایان نمایش ساختار درختی   "
    echo "========================================="
}

# اجرای تابع اصلی
main "$@"