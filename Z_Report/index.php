<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه مدیریت و یکپارچه‌سازی فایل‌ها</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #ffffff;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 20px;
            height: calc(100vh - 40px);
        }

        .header {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo i {
            font-size: 2.5rem;
            color: #4dabf7;
            filter: drop-shadow(0 0 10px rgba(77, 171, 247, 0.5));
        }

        .logo h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .stats {
            display: flex;
            gap: 20px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px 25px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-width: 150px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #4dabf7;
            text-shadow: 0 0 10px rgba(77, 171, 247, 0.5);
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 5px;
        }

        .panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .panel-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #4dabf7;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .panel-title i {
            font-size: 1.3rem;
        }

        /* دراپ‌داون انتخاب درایو */
        .drive-selector {
            margin-bottom: 20px;
        }

        .drive-label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .drive-dropdown {
            width: 100%;
            padding: 10px 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(77, 171, 247, 0.5);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .drive-dropdown:focus {
            outline: none;
            border-color: #4dabf7;
            box-shadow: 0 0 0 2px rgba(77, 171, 247, 0.3);
        }

        /* ساختار درختی */
        .tree-container {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* درخت سمت چپ */
        .tree {
            list-style: none;
            padding: 0;
            margin: 0;
            direction: ltr; /* درخت از چپ به راست */
            text-align: left;
        }

        .tree ul {
            list-style: none;
            padding-left: 25px;
            margin: 5px 0;
            border-left: 1px solid rgba(77, 171, 247, 0.3);
        }

        .tree li {
            margin: 3px 0;
            position: relative;
        }

        .tree-node {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            border: 1px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
            min-height: 36px;
        }

        .tree-node:hover {
            background: rgba(77, 171, 247, 0.15);
            border-color: rgba(77, 171, 247, 0.3);
        }

        .tree-node.selected {
            background: rgba(77, 171, 247, 0.2);
            border-color: rgba(77, 171, 247, 0.5);
        }

        .node-icon {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }

        .node-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .node-name {
            font-weight: 500;
            font-size: 0.95rem;
            color: white;
        }

        .toggle-icon {
            color: #ffb74d;
            font-size: 0.9rem;
            cursor: pointer;
            transition: transform 0.2s;
            width: 20px;
            text-align: center;
            margin-left: 10px;
        }

        .toggle-icon.rotated {
            transform: rotate(90deg);
        }

        .checkbox-container {
            margin-left: 10px;
        }

        .file-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* فایل‌های انتخاب شده */
        .selected-files-container {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .empty-selection {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.6);
        }

        .empty-selection i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .selected-file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .selected-file-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-5px);
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .file-details {
            flex: 1;
        }

        .file-name {
            font-weight: 500;
            margin-bottom: 3px;
            color: white;
        }

        .file-size {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .remove-file {
            background: rgba(244, 67, 54, 0.2);
            color: #f44336;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-file:hover {
            background: rgba(244, 67, 54, 0.4);
            transform: scale(1.1);
        }

        /* تنظیمات */
        .settings-container {
            margin-bottom: 20px;
        }

        .setting-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .setting-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 0.95rem;
        }

        .setting-icon {
            color: #4dabf7;
            font-size: 1.1rem;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            right: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #4dabf7;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(-26px);
        }

        /* دکمه‌ها */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: auto;
        }

        .btn {
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #4dabf7, #339af0);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(77, 171, 247, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        /* پیش‌نمایش فایل */
        .file-preview-panel {
            margin-top: 20px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            padding: 15px;
            border: 1px solid rgba(77, 171, 247, 0.3);
        }

        .file-preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .file-preview-title {
            color: #4dabf7;
            font-weight: 600;
            font-size: 1rem;
        }

        .file-content {
            font-family: monospace;
            font-size: 0.85rem;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.9);
            white-space: pre-wrap;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        /* اسکرول بار */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(77, 171, 247, 0.5);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(77, 171, 247, 0.7);
        }

        /* رسپانسیو */
        @media (max-width: 1200px) {
            .container {
                grid-template-columns: 1fr;
                height: auto;
            }

            .panel {
                margin-bottom: 20px;
                min-height: 400px;
            }
        }

        /* آیکون‌ها */
        .folder-icon {
            color: #ffb74d;
        }

        .file-icon {
            color: #4dabf7;
        }

        .pdf-icon {
            color: #f44336;
        }

        .doc-icon {
            color: #2196f3;
        }

        .img-icon {
            color: #4caf50;
        }

        .zip-icon {
            color: #ff9800;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- هدر -->
        <div class="header">
            <div class="logo">
                <i class="fas fa-file-alt"></i>
                <h1>سامانه مدیریت و یکپارچه‌سازی فایل‌ها</h1>
            </div>
            <div class="stats">
                <div class="stat-box">
                    <div class="stat-value" id="total-size">0 MB</div>
                    <div class="stat-label">حجم کل انتخاب‌ها</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value" id="selected-count">0</div>
                    <div class="stat-label">فایل‌های انتخاب شده</div>
                </div>
            </div>
        </div>

        <!-- پنل سمت راست: تنظیمات -->
        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-sliders-h"></i>
                تنظیمات یکپارچه‌سازی
            </div>

            <div class="settings-container">
                <div class="setting-item">
                    <div class="setting-label">
                        <i class="fas fa-minus setting-icon"></i>
                        <span>جداکننده میانی (خط نیم)</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked id="separator-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="setting-label">
                        <i class="fas fa-exclamation-triangle setting-icon"></i>
                        <span>خطا می‌افزاید</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="error-add-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="setting-label">
                        <i class="fas fa-fire setting-icon"></i>
                        <span>فشرده‌سازی خونگر</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked id="compress-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="setting-label">
                        <i class="fas fa-shield-alt setting-icon"></i>
                        <span>رمزگذاری فایل یکپارچه</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked id="encrypt-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" id="save-settings-btn">
                    <i class="fas fa-save"></i>
                    ذخیره تنظیمات
                </button>
                <button class="btn btn-secondary" id="reset-settings-btn">
                    <i class="fas fa-undo"></i>
                    بازنشانی تنظیمات
                </button>
            </div>
        </div>

        <!-- پنل مرکز: فایل‌های انتخاب شده -->
        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-star"></i>
                فایل‌های انتخاب شده
            </div>

            <div class="selected-files-container" id="selected-files-container">
                <div class="empty-selection" id="empty-selection">
                    <i class="fas fa-inbox"></i>
                    <p>حضور فایل انتخاب شده است</p>
                    <p style="font-size: 0.9rem; margin-top: 10px; opacity: 0.8;">فایل‌ها را از ساختار درخت انتخاب کنید</p>
                </div>
            </div>

            <div class="file-preview-panel" id="file-preview-panel" style="display: none;">
                <div class="file-preview-header">
                    <div class="file-preview-title" id="file-preview-title">پیش‌نمایش فایل</div>
                    <button class="btn-secondary" id="close-preview-btn" style="padding: 5px 10px; font-size: 0.8rem;">بستن</button>
                </div>
                <div class="file-content" id="file-preview-content">
                    محتوای فایل اینجا نمایش داده می‌شود...
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" id="integrate-btn">
                    <i class="fas fa-file-archive"></i>
                    ایجاد فایل یکپارچه
                </button>
                <button class="btn btn-secondary" id="clear-all-btn">
                    <i class="fas fa-trash-alt"></i>
                    حذف همه انتخاب‌ها
                </button>
            </div>
        </div>

        <!-- پنل سمت چپ: ساختار درختی -->
        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-sitemap"></i>
                ساختار درخت فایل‌ها (اکسپلورر)
            </div>

            <div class="drive-selector">
                <label class="drive-label">انتخاب درایو:</label>
                <select class="drive-dropdown" id="drive-select">
                    <option value="drive1">درایو C: سیستم</option>
                    <option value="drive2">درایو D: اسناد</option>
                    <option value="drive3">درایو E: پروژه‌ها</option>
                    <option value="drive4">درایو F: بایگانی</option>
                </select>
            </div>

            <div class="tree-container">
                <ul class="tree" id="file-tree">
                    <!-- ساختار درختی اینجا ساخته می‌شود -->
                </ul>
            </div>
        </div>
    </div>

    <script>
        // داده‌های ساختار درختی
        const fileTreeData = {
            drive1: [
                {
                    id: 'windows',
                    name: 'Windows',
                    type: 'folder',
                    expanded: false,
                    children: [
                        {
                            id: 'system32',
                            name: 'System32',
                            type: 'folder',
                            expanded: false,
                            children: [
                                { id: 'winlogon', name: 'winlogon.exe', type: 'file', size: 0.5, content: 'فایل اجرایی سیستم ورود به ویندوز' },
                                { id: 'kernel', name: 'kernel32.dll', type: 'file', size: 1.2, content: 'کتابخانه سیستمی کرنل ویندوز' }
                            ]
                        },
                        {
                            id: 'fonts',
                            name: 'Fonts',
                            type: 'folder',
                            expanded: false,
                            children: [
                                { id: 'arial', name: 'arial.ttf', type: 'file', size: 0.8, content: 'فونت Arial' },
                                { id: 'times', name: 'times.ttf', type: 'file', size: 0.9, content: 'فونت Times New Roman' }
                            ]
                        }
                    ]
                },
                {
                    id: 'program-files',
                    name: 'Program Files',
                    type: 'folder',
                    expanded: false,
                    children: [
                        {
                            id: 'adobe',
                            name: 'Adobe',
                            type: 'folder',
                            expanded: false,
                            children: [
                                { id: 'acrobat', name: 'Acrobat Reader.pdf', type: 'file', size: 15.3, content: 'نرم افزار خواندن فایل‌های PDF' },
                                { id: 'photoshop', name: 'Photoshop.exe', type: 'file', size: 85.2, content: 'نرم افزار ویرایش تصویر' }
                            ]
                        },
                        {
                            id: 'microsoft',
                            name: 'Microsoft Office',
                            type: 'folder',
                            expanded: false,
                            children: [
                                { id: 'word', name: 'Word.exe', type: 'file', size: 42.7, content: 'نرم افزار واژه پرداز' },
                                { id: 'excel', name: 'Excel.exe', type: 'file', size: 38.9, content: 'نرم افزار صفحه گسترده' }
                            ]
                        }
                    ]
                },
                {
                    id: 'users',
                    name: 'Users',
                    type: 'folder',
                    expanded: true,
                    children: [
                        {
                            id: 'user1',
                            name: 'کاربر اول',
                            type: 'folder',
                            expanded: false,
                            children: [
                                {
                                    id: 'desktop',
                                    name: 'Desktop',
                                    type: 'folder',
                                    expanded: false,
                                    children: [
                                        { id: 'doc1', name: 'گزارش.docx', type: 'file', size: 2.3, content: 'گزارش هفتگی پروژه' },
                                        { id: 'image1', name: 'تصویر.jpg', type: 'file', size: 3.8, content: 'تصویر نمونه' }
                                    ]
                                },
                                {
                                    id: 'documents',
                                    name: 'Documents',
                                    type: 'folder',
                                    expanded: false,
                                    children: [
                                        { id: 'project', name: 'پروژه.zip', type: 'file', size: 25.4, content: 'فایل فشرده پروژه' },
                                        { id: 'report', name: 'گزارش مالی.pdf', type: 'file', size: 5.7, content: 'گزارش مالی سه‌ماهه' }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ],
            drive2: [
                {
                    id: 'documents',
                    name: 'اسناد',
                    type: 'folder',
                    expanded: false,
                    children: [
                        { id: 'doc1', name: 'قرارداد.pdf', type: 'file', size: 3.2, content: 'قرارداد همکاری' },
                        { id: 'doc2', name: 'گزارش سالانه.docx', type: 'file', size: 4.1, content: 'گزارش عملکرد سالانه' }
                    ]
                }
            ]
        };

        // وضعیت برنامه
        let selectedFiles = [];
        let currentDrive = 'drive1';

        // تابع ایجاد آیکون
        function getIcon(type, name = '') {
            if (type === 'folder') {
                return { class: 'fas fa-folder', color: 'folder-icon' };
            } else if (name.includes('.pdf')) {
                return { class: 'fas fa-file-pdf', color: 'pdf-icon' };
            } else if (name.includes('.doc')) {
                return { class: 'fas fa-file-word', color: 'doc-icon' };
            } else if (name.includes('.exe') || name.includes('.dll')) {
                return { class: 'fas fa-cog', color: 'file-icon' };
            } else if (name.includes('.jpg') || name.includes('.png')) {
                return { class: 'fas fa-file-image', color: 'img-icon' };
            } else if (name.includes('.zip') || name.includes('.rar')) {
                return { class: 'fas fa-file-archive', color: 'zip-icon' };
            } else {
                return { class: 'fas fa-file', color: 'file-icon' };
            }
        }

        // تابع ایجاد گره درختی
        function createTreeNode(item, level = 0) {
            const icon = getIcon(item.type, item.name);
            const hasChildren = item.children && item.children.length > 0;
            const isExpanded = item.expanded || false;

            let html = `
                <li data-id="${item.id}" data-type="${item.type}" data-name="${item.name}" data-size="${item.size || 0}" data-content="${item.content || ''}">
                    <div class="tree-node" style="margin-left: ${level * 15}px;">
                        <div class="node-icon ${icon.color}">
                            <i class="${icon.class}"></i>
                        </div>
                        <div class="node-content">
                            <span class="node-name">${item.name}</span>
                            <div>
                                ${hasChildren ?
                                    `<i class="fas fa-chevron-left toggle-icon ${isExpanded ? 'rotated' : ''}" data-toggle="${item.id}"></i>` :
                                    (item.type === 'file' ?
                                        `<div class="checkbox-container">
                                            <input type="checkbox" class="file-checkbox" id="cb-${item.id}">
                                        </div>` : '')
                                }
                            </div>
                        </div>
                    </div>
            `;

            if (hasChildren && isExpanded) {
                html += `<ul>`;
                item.children.forEach(child => {
                    html += createTreeNode(child, level + 1);
                });
                html += `</ul>`;
            }

            html += `</li>`;
            return html;
        }

        // بارگذاری درخت
        function loadTree() {
            const treeContainer = document.getElementById('file-tree');
            const data = fileTreeData[currentDrive] || [];

            let html = '';
            data.forEach(item => {
                html += createTreeNode(item);
            });

            treeContainer.innerHTML = html;
            attachTreeEvents();
        }

        // اتصال رویدادها
        function attachTreeEvents() {
            // کلیک روی گره برای انتخاب
            document.querySelectorAll('.tree-node').forEach(node => {
                node.addEventListener('click', function(e) {
                    if (e.target.classList.contains('toggle-icon') ||
                        e.target.classList.contains('file-checkbox') ||
                        e.target.closest('.toggle-icon') ||
                        e.target.closest('.file-checkbox')) {
                        return;
                    }

                    const li = this.closest('li');
                    const type = li.getAttribute('data-type');
                    const name = li.getAttribute('data-name');

                    // حذف انتخاب از همه
                    document.querySelectorAll('.tree-node.selected').forEach(n => {
                        n.classList.remove('selected');
                    });

                    // انتخاب این گره
                    this.classList.add('selected');

                    if (type === 'file') {
                        showFilePreview(li);
                    }
                });
            });

            // باز و بسته کردن پوشه‌ها
            document.querySelectorAll('.toggle-icon').forEach(icon => {
                icon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const itemId = this.getAttribute('data-toggle');

                    // پیدا کردن آیتم و تغییر وضعیت
                    function toggleItem(items) {
                        for (let item of items) {
                            if (item.id === itemId) {
                                item.expanded = !item.expanded;
                                return true;
                            }
                            if (item.children) {
                                if (toggleItem(item.children)) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    }

                    toggleItem(fileTreeData[currentDrive]);
                    loadTree();
                });
            });

            // چک‌باکس فایل‌ها
            document.querySelectorAll('.file-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function(e) {
                    e.stopPropagation();
                    const li = this.closest('li');
                    const id = li.getAttribute('data-id');
                    const name = li.getAttribute('data-name');
                    const size = parseFloat(li.getAttribute('data-size')) || 0;
                    const content = li.getAttribute('data-content');

                    if (this.checked) {
                        addSelectedFile(id, name, size, content);
                    } else {
                        removeSelectedFile(id);
                    }

                    updateStats();
                });
            });
        }

        // اضافه کردن فایل انتخاب شده
        function addSelectedFile(id, name, size, content) {
            if (selectedFiles.some(f => f.id === id)) return;

            selectedFiles.push({ id, name, size, content });
            updateSelectedFilesList();
        }

        // حذف فایل انتخاب شده
        function removeSelectedFile(id) {
            selectedFiles = selectedFiles.filter(f => f.id !== id);
            updateSelectedFilesList();

            // تیک چک‌باکس را بردار
            const checkbox = document.querySelector(`#cb-${id}`);
            if (checkbox) checkbox.checked = false;
        }

        // بروزرسانی لیست فایل‌های انتخاب شده
        function updateSelectedFilesList() {
            const container = document.getElementById('selected-files-container');
            const emptyDiv = document.getElementById('empty-selection');

            if (selectedFiles.length === 0) {
                container.innerHTML = `
                    <div class="empty-selection" id="empty-selection">
                        <i class="fas fa-inbox"></i>
                        <p>حضور فایل انتخاب شده است</p>
                        <p style="font-size: 0.9rem; margin-top: 10px; opacity: 0.8;">فایل‌ها را از ساختار درخت انتخاب کنید</p>
                    </div>
                `;
                return;
            }

            let html = '';
            selectedFiles.forEach(file => {
                const icon = getIcon('file', file.name);
                html += `
                    <div class="selected-file-item" data-id="${file.id}">
                        <div class="file-info">
                            <div class="node-icon ${icon.color}">
                                <i class="${icon.class}"></i>
                            </div>
                            <div class="file-details">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${file.size.toFixed(1)} MB</div>
                            </div>
                        </div>
                        <button class="remove-file" data-id="${file.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });

            container.innerHTML = html;

            // اضافه کردن رویداد به دکمه‌های حذف
            document.querySelectorAll('.remove-file').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fileId = this.getAttribute('data-id');
                    removeSelectedFile(fileId);
                    updateStats();
                });
            });
        }

        // نمایش پیش‌نمایش فایل
        function showFilePreview(li) {
            const name = li.getAttribute('data-name');
            const content = li.getAttribute('data-content');

            document.getElementById('file-preview-title').textContent = name;
            document.getElementById('file-preview-content').textContent = content || 'محتوایی برای نمایش وجود ندارد';
            document.getElementById('file-preview-panel').style.display = 'block';
        }

        // بروزرسانی آمار
        function updateStats() {
            const totalSize = selectedFiles.reduce((sum, file) => sum + file.size, 0);
            document.getElementById('total-size').textContent = totalSize.toFixed(1) + ' MB';
            document.getElementById('selected-count').textContent = selectedFiles.length;
        }

        // راه‌اندازی اولیه
        document.addEventListener('DOMContentLoaded', function() {
            // بارگذاری درخت اولیه
            loadTree();

            // تغییر درایو
            document.getElementById('drive-select').addEventListener('change', function() {
                currentDrive = this.value;
                loadTree();
                selectedFiles = [];
                updateSelectedFilesList();
                updateStats();
            });

            // دکمه ایجاد فایل یکپارچه
            document.getElementById('integrate-btn').addEventListener('click', function() {
                if (selectedFiles.length === 0) {
                    alert('لطفاً حداقل یک فایل انتخاب کنید.');
                    return;
                }

                const separatorEnabled = document.getElementById('separator-toggle').checked;
                const compressEnabled = document.getElementById('compress-toggle').checked;
                const encryptEnabled = document.getElementById('encrypt-toggle').checked;

                let content = 'فایل یکپارچه ایجاد شده:\n\n';
                selectedFiles.forEach(file => {
                    content += `--- ${file.name} ---\n`;
                    content += file.content + '\n\n';
                });

                content += `\nتنظیمات اعمال شده:\n`;
                content += `- جداکننده میانی: ${separatorEnabled ? 'فعال' : 'غیرفعال'}\n`;
                content += `- فشرده‌سازی: ${compressEnabled ? 'فعال' : 'غیرفعال'}\n`;
                content += `- رمزگذاری: ${encryptEnabled ? 'فعال' : 'غیرفعال'}\n`;

                document.getElementById('file-preview-title').textContent = 'فایل یکپارچه';
                document.getElementById('file-preview-content').textContent = content;
                document.getElementById('file-preview-panel').style.display = 'block';

                alert(`فایل یکپارچه با موفقیت ایجاد شد!\nتعداد فایل‌ها: ${selectedFiles.length}`);
            });

            // دکمه حذف همه
            document.getElementById('clear-all-btn').addEventListener('click', function() {
                selectedFiles = [];
                updateSelectedFilesList();
                updateStats();

                // تیک همه چک‌باکس‌ها را بردار
                document.querySelectorAll('.file-checkbox:checked').forEach(cb => {
                    cb.checked = false;
                });
            });

            // دکمه بستن پیش‌نمایش
            document.getElementById('close-preview-btn').addEventListener('click', function() {
                document.getElementById('file-preview-panel').style.display = 'none';
            });

            // دکمه ذخیره تنظیمات
            document.getElementById('save-settings-btn').addEventListener('click', function() {
                alert('تنظیمات با موفقیت ذخیره شدند.');
            });

            // دکمه بازنشانی تنظیمات
            document.getElementById('reset-settings-btn').addEventListener('click', function() {
                document.getElementById('separator-toggle').checked = true;
                document.getElementById('error-add-toggle').checked = false;
                document.getElementById('compress-toggle').checked = true;
                document.getElementById('encrypt-toggle').checked = true;
                alert('تنظیمات به حالت پیش‌فرض بازگردانده شدند.');
            });
        });
    </script>
</body>
</html>
