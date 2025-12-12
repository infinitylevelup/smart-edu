@extends('diagnosis.layout')

@section('title', 'ادغام هوشمند فایل‌ها')

@section('breadcrumb', 'ادغام فایل‌ها / MergeMaster')

@section('content')
    <!-- استایل‌های اختصاصی -->
    <style>
        /* استایل‌های پایه */
        :root {
            --merge-primary: #6366f1;
            --merge-secondary: #8b5cf6;
            --merge-accent: #f59e0b;
            --merge-success: #10b981;
            --merge-danger: #ef4444;
            --merge-dark: #1e293b;
            --merge-light: #f8fafc;
            --merge-border: #e2e8f0;
        }

        /* گرید اصلی */
        .merge-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            height: calc(100vh - 200px);
        }

        @media (max-width: 1024px) {
            .merge-grid {
                grid-template-columns: 1fr;
                height: auto;
            }
        }

        /* پنل انتخاب فایل‌ها */
        .file-panel {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid var(--merge-border);
            display: flex;
            flex-direction: column;
        }

        .preview-panel {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid var(--merge-border);
            display: flex;
            flex-direction: column;
        }

        /* هدر پنل‌ها */
        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--merge-border);
        }

        .panel-header h2 {
            margin: 0;
            color: var(--merge-dark);
            font-size: 20px;
        }

        /* جستجوی پیشرفته */
        .search-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 2px solid var(--merge-border);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
            background: var(--merge-light);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--merge-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* لیست فایل‌های پیشنهادی */
        .suggestions-list {
            max-height: 300px;
            overflow-y: auto;
            background: white;
            border: 1px solid var(--merge-border);
            border-radius: 12px;
            margin-top: 10px;
            display: none;
            position: absolute;
            width: 100%;
            z-index: 100;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .suggestions-list.active {
            display: block;
        }

        .suggestion-item {
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 1px solid var(--merge-border);
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .suggestion-item:hover {
            background: var(--merge-light);
        }

        .suggestion-item .file-icon {
            margin-left: 10px;
            color: #94a3b8;
        }

        .suggestion-item .file-path {
            flex: 1;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 13px;
        }

        .suggestion-item .file-size {
            color: #64748b;
            font-size: 12px;
        }

        /* لیست انتخاب شده‌ها */
        .selected-list {
            flex: 1;
            overflow-y: auto;
            margin-top: 20px;
            border: 2px dashed var(--merge-border);
            border-radius: 12px;
            padding: 15px;
            min-height: 200px;
        }

        .selected-item {
            background: white;
            border: 1px solid var(--merge-border);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            cursor: move;
        }

        .selected-item:hover {
            border-color: var(--merge-primary);
            transform: translateX(-5px);
        }

        .selected-item .drag-handle {
            color: #cbd5e1;
            margin-left: 15px;
            cursor: grab;
        }

        .selected-item .file-info {
            flex: 1;
        }

        .selected-item .file-name {
            font-weight: 600;
            color: var(--merge-dark);
            margin-bottom: 5px;
        }

        .selected-item .file-path {
            font-size: 12px;
            color: #64748b;
            font-family: 'Monaco', 'Consolas', monospace;
        }

        .selected-item .remove-btn {
            background: none;
            border: none;
            color: var(--merge-danger);
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .selected-item .remove-btn:hover {
            background: #fee2e2;
        }

        /* پریست‌های سریع */
        .presets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .preset-card {
            background: var(--merge-light);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .preset-card:hover {
            border-color: var(--merge-primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .preset-card .preset-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .preset-card .preset-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--merge-dark);
        }

        .preset-card .preset-desc {
            font-size: 12px;
            color: #64748b;
        }

        /* پیش‌نمایش */
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stats-badge {
            background: var(--merge-primary);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .preview-content {
            flex: 1;
            overflow-y: auto;
            background: #0f172a;
            border-radius: 12px;
            padding: 20px;
            font-family: 'Monaco', 'Consolas', monospace;
            color: #e2e8f0;
            white-space: pre-wrap;
            line-height: 1.6;
            font-size: 13px;
        }

        .file-header {
            color: #60a5fa;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #334155;
        }

        /* کنترل‌های پایین */
        .controls-bar {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--merge-border);
        }

        .action-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-copy {
            background: var(--merge-success);
            color: white;
        }

        .btn-copy:hover {
            background: #0d9668;
        }

        .btn-download {
            background: var(--merge-primary);
            color: white;
        }

        .btn-download:hover {
            background: #4f46e5;
        }

        .btn-share {
            background: var(--merge-accent);
            color: white;
        }

        .btn-share:hover {
            background: #d97706;
        }

        .btn-clear {
            background: var(--merge-danger);
            color: white;
        }

        .btn-clear:hover {
            background: #dc2626;
        }

        /* فایل‌های اخیر */
        .recent-files {
            margin-top: 25px;
        }

        .recent-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .recent-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
        }

        .recent-file {
            background: var(--merge-light);
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .recent-file:hover {
            border-color: var(--merge-primary);
            background: white;
        }

        .recent-file .file-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--merge-dark);
        }

        .recent-file .file-path {
            color: #64748b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* مودال تنظیمات پیشرفته */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        /* انیمیشن‌ها */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 0.5s ease-in-out;
        }
    </style>

    <div class="merge-grid">
        <!-- پنل سمت راست: انتخاب فایل‌ها -->
        <div class="file-panel">
            <div class="panel-header">
                <h2>🔍 انتخاب فایل‌ها</h2>
                <button onclick="openAdvancedModal()" class="btn" style="background: var(--merge-accent); color: white;">
                    ⚙️ تنظیمات پیشرفته
                </button>
            </div>

            <!-- جستجوی هوشمند -->
            <div class="search-wrapper">
                <span class="search-icon">🔎</span>
                <input type="text"
                       class="search-input"
                       placeholder="جستجوی فایل... (مثال: UserController، *.blade.php، app/Http/*)"
                       id="fileSearch"
                       onkeyup="searchFiles(this.value)"
                       autocomplete="off">

                <div class="suggestions-list" id="suggestionsList"></div>
            </div>

            <!-- پریست‌های سریع -->
            <h3 style="margin: 20px 0 15px 0; color: var(--merge-dark);">🚀 انتخاب سریع</h3>
            <div class="presets-grid">
                <div class="preset-card" onclick="loadPreset('full_mvc')">
                    <div class="preset-icon">🎭</div>
                    <div class="preset-title">MVC کامل</div>
                    <div class="preset-desc">Model + View + Controller</div>
                </div>

                <div class="preset-card" onclick="loadPreset('controller_view')">
                    <div class="preset-icon">🔄</div>
                    <div class="preset-title">Controller + View</div>
                    <div class="preset-desc">همه Viewهای مرتبط</div>
                </div>

                <div class="preset-card" onclick="loadPreset('api_routes')">
                    <div class="preset-icon">🌐</div>
                    <div class="preset-title">API Routes</div>
                    <div class="preset-desc">همه endpointهای API</div>
                </div>

                <div class="preset-card" onclick="loadPreset('config_files')">
                    <div class="preset-icon">⚙️</div>
                    <div class="preset-title">تنظیمات</div>
                    <div class="preset-desc">همه فایل‌های config</div>
                </div>

                <div class="preset-card" onclick="loadPreset('error_files')">
                    <div class="preset-icon">🐛</div>
                    <div class="preset-title">خطاهای اخیر</div>
                    <div class="preset-desc">فایل‌های دارای error</div>
                </div>

                <div class="preset-card" onclick="loadCustomPreset()">
                    <div class="preset-icon">✨</div>
                    <div class="preset-title">سفارشی</div>
                    <div class="preset-desc">ایجاد پریست جدید</div>
                </div>
            </div>

            <!-- لیست فایل‌های انتخاب شده -->
            <h3 style="margin: 25px 0 15px 0; color: var(--merge-dark);">
                📋 فایل‌های انتخاب شده
                <span id="selectedCount" style="background: var(--merge-primary); color: white; padding: 2px 10px; border-radius: 12px; font-size: 12px;">0</span>
            </h3>

            <div class="selected-list" id="selectedList" ondrop="drop(event)" ondragover="allowDrop(event)">
                <!-- فایل‌های انتخاب شده اینجا نمایش داده می‌شوند -->
                <div id="emptyMessage" style="text-align: center; color: #94a3b8; padding: 40px 20px;">
                    <div style="font-size: 48px; margin-bottom: 15px;">📁</div>
                    <h4 style="margin: 0 0 10px 0; color: #64748b;">هنوز فایلی انتخاب نکرده‌اید</h4>
                    <p style="margin: 0; font-size: 14px;">فایل‌ها را جستجو کنید یا از پریست‌های بالا استفاده کنید</p>
                </div>
            </div>

            <!-- فایل‌های اخیر -->
            <div class="recent-files">
                <div class="recent-header">
                    <h3 style="margin: 0; color: var(--merge-dark);">🕐 فایل‌های اخیر</h3>
                    <button onclick="clearRecentFiles()" style="background: none; border: none; color: #64748b; cursor: pointer; font-size: 12px;">
                        پاک کردن
                    </button>
                </div>
                <div class="recent-list" id="recentFiles">
                    <!-- فایل‌های اخیر به صورت داینامیک لود می‌شوند -->
                </div>
            </div>
        </div>

        <!-- پنل سمت چپ: پیش‌نمایش -->
        <div class="preview-panel">
            <div class="preview-header">
                <h2>👁️ پیش‌نمایش ادغام</h2>
                <div class="stats-badge" id="previewStats">
                    ⏳ در حال آماده‌سازی...
                </div>
            </div>

            <div class="preview-content" id="previewContent">
                <div style="color: #94a3b8; text-align: center; padding: 40px 20px;">
                    <div style="font-size: 48px; margin-bottom: 15px;">✨</div>
                    <h4 style="margin: 0 0 10px 0; color: #cbd5e1;">پیش‌نمایش آماده است</h4>
                    <p style="margin: 0; font-size: 14px;">فایل‌ها را انتخاب کنید تا پیش‌نمایش اینجا نمایش داده شود</p>
                </div>
            </div>

            <!-- کنترل‌های پایین -->
            <div class="controls-bar">
                <button class="action-btn btn-copy" onclick="copyToClipboard()" id="copyBtn">
                    📋 کپی محتوا
                </button>
                <button class="action-btn btn-download" onclick="downloadMerged()">
                    ⬇️ دانلود فایل
                </button>
                <button class="action-btn btn-share" onclick="shareWithAI()">
                    🤖 اشتراک با AI
                </button>
                <button class="action-btn btn-clear" onclick="clearAll()">
                    🗑️ پاک کردن همه
                </button>
            </div>
        </div>
    </div>

    <!-- مودال تنظیمات پیشرفته -->
    <div class="modal-overlay" id="advancedModal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="margin: 0; color: var(--merge-dark);">⚙️ تنظیمات پیشرفته ادغام</h2>
                <button onclick="closeAdvancedModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- تنظیمات فیلتر -->
                <div>
                    <h3 style="margin: 0 0 15px 0; color: var(--merge-dark);">🎯 فیلتر فایل‌ها</h3>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">نوع فایل‌ها:</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" checked onchange="updateFileTypes()"> .php
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" checked onchange="updateFileTypes()"> .blade.php
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" checked onchange="updateFileTypes()"> .js
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" checked onchange="updateFileTypes()"> .css
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" onchange="updateFileTypes()"> .json
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" onchange="updateFileTypes()"> .env
                            </label>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">حذف پوشه‌ها:</label>
                        <input type="text"
                               style="width: 100%; padding: 10px; border: 1px solid var(--merge-border); border-radius: 8px;"
                               value="vendor, node_modules, .git, storage, public/vendor"
                               id="excludeDirs">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">الگوی جستجو:</label>
                        <input type="text"
                               style="width: 100%; padding: 10px; border: 1px solid var(--merge-border); border-radius: 8px;"
                               placeholder="مثال: *Controller.php, *Service.php"
                               id="searchPattern">
                    </div>
                </div>

                <!-- تنظیمات خروجی -->
                <div>
                    <h3 style="margin: 0 0 15px 0; color: var(--merge-dark);">📤 تنظیمات خروجی</h3>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">قالب جداکننده:</label>
                        <select style="width: 100%; padding: 10px; border: 1px solid var(--merge-border); border-radius: 8px;"
                                onchange="updateSeparator()" id="separatorType">
                            <option value="simple">ساده (==========)</option>
                            <option value="detailed" selected>مفصل (با هدر کامل)</option>
                            <option value="compact">فشرده (بدون جداکننده)</option>
                            <option value="markdown">Markdown (با کد)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">حداکثر حجم فایل (MB):</label>
                        <input type="range"
                               min="1"
                               max="50"
                               value="10"
                               style="width: 100%;"
                               oninput="document.getElementById('maxSizeValue').textContent = this.value + 'MB'"
                               id="maxSize">
                        <div style="text-align: center; color: #64748b; margin-top: 5px;">
                            <span id="maxSizeValue">10MB</span>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">شماره خط:</label>
                        <label style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" checked id="lineNumbers">
                            نمایش شماره خطوط
                        </label>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">سایر تنظیمات:</label>
                        <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <input type="checkbox" checked id="trimWhitespace">
                            حذف فضاهای خالی انتهای خطوط
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" id="includeEmptyLines">
                            حذف خطوط خالی
                        </label>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--merge-border);">
                <button onclick="applyAdvancedSettings()" class="action-btn btn-download" style="flex: 2;">
                    💾 اعمال تنظیمات
                </button>
                <button onclick="closeAdvancedModal()" class="action-btn btn-clear" style="flex: 1;">
                    انصراف
                </button>
            </div>
        </div>
    </div>

    <!-- اسکریپت اصلی -->
    <script>
        // داده‌های نمونه
        let selectedFiles = [];
        let allFiles = [];
        let recentFiles = JSON.parse(localStorage.getItem('recentFiles') || '[]');

        // بارگذاری اولیه
        document.addEventListener('DOMContentLoaded', function() {
            loadRecentFiles();
            updatePreview();

            // بارگذاری فایل‌ها از سرور
            fetchFilesList();
        });

        // جستجوی فایل‌ها
        async function searchFiles(query) {
            if (query.length < 2) {
                hideSuggestions();
                return;
            }

            const suggestionsList = document.getElementById('suggestionsList');
            suggestionsList.innerHTML = '';

            // جستجوی محلی
            const filtered = allFiles.filter(file =>
                file.path.toLowerCase().includes(query.toLowerCase()) ||
                file.name.toLowerCase().includes(query.toLowerCase())
            ).slice(0, 10);

            if (filtered.length > 0) {
                filtered.forEach(file => {
                    const item = document.createElement('div');
                    item.className = 'suggestion-item';
                    item.innerHTML = `
                        <span class="file-icon">${getFileIcon(file.type)}</span>
                        <span class="file-path">${file.path}</span>
                        <span class="file-size">${formatFileSize(file.size)}</span>
                    `;
                    item.onclick = () => addFileToList(file);
                    suggestionsList.appendChild(item);
                });

                showSuggestions();
            } else {
                // جستجو در سرور
                try {
                    const response = await fetch(`/diagnosis/search-files?q=${encodeURIComponent(query)}`);
                    const files = await response.json();

                    files.forEach(file => {
                        const item = document.createElement('div');
                        item.className = 'suggestion-item';
                        item.innerHTML = `
                            <span class="file-icon">📄</span>
                            <span class="file-path">${file.path}</span>
                            <span class="file-size">${formatFileSize(file.size)}</span>
                        `;
                        item.onclick = () => addFileToList(file);
                        suggestionsList.appendChild(item);
                    });

                    showSuggestions();
                } catch (error) {
                    console.error('Error searching files:', error);
                }
            }
        }

        function showSuggestions() {
            const list = document.getElementById('suggestionsList');
            list.classList.add('active');
            list.classList.add('fade-in');
        }

        function hideSuggestions() {
            const list = document.getElementById('suggestionsList');
            list.classList.remove('active');
        }

        // اضافه کردن فایل به لیست
        function addFileToList(file) {
            if (selectedFiles.some(f => f.path === file.path)) {
                showToast('این فایل قبلاً اضافه شده است', 'warning');
                return;
            }

            selectedFiles.push(file);
            hideSuggestions();
            updateSelectedList();
            updatePreview();
            addToRecentFiles(file);
            showToast('فایل اضافه شد', 'success');
        }

        // حذف فایل از لیست
        function removeFile(path) {
            selectedFiles = selectedFiles.filter(f => f.path !== path);
            updateSelectedList();
            updatePreview();
        }

        // آپدیت لیست انتخاب شده
        function updateSelectedList() {
            const list = document.getElementById('selectedList');
            const emptyMsg = document.getElementById('emptyMessage');
            const countBadge = document.getElementById('selectedCount');

            countBadge.textContent = selectedFiles.length;

            if (selectedFiles.length === 0) {
                emptyMsg.style.display = 'block';
                list.innerHTML = '';
                list.appendChild(emptyMsg);
                return;
            }

            emptyMsg.style.display = 'none';

            list.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const item = document.createElement('div');
                item.className = 'selected-item fade-in';
                item.draggable = true;
                item.id = `file-${index}`;
                item.ondragstart = (e) => drag(e, index);

                item.innerHTML = `
                    <div class="drag-handle">⋮⋮</div>
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-path">${file.path}</div>
                    </div>
                    <button class="remove-btn" onclick="removeFile('${file.path}')">
                        ❌
                    </button>
                `;
                list.appendChild(item);
            });
        }

        // Drag & Drop
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev, index) {
            ev.dataTransfer.setData("text", index);
        }

        function drop(ev) {
            ev.preventDefault();
            const fromIndex = ev.dataTransfer.getData("text");
            const toElement = ev.target.closest('.selected-item');

            if (toElement) {
                const toIndex = Array.from(document.querySelectorAll('.selected-item')).indexOf(toElement);
                if (fromIndex !== toIndex) {
                    // تغییر ترتیب
                    const [movedFile] = selectedFiles.splice(fromIndex, 1);
                    selectedFiles.splice(toIndex, 0, movedFile);
                    updateSelectedList();
                    updatePreview();
                }
            }
        }

        // لود پریست‌ها
        function loadPreset(presetName) {
            showToast(`در حال بارگذاری پریست ${presetName}...`, 'info');

            // درخواست به سرور برای دریافت فایل‌های پریست
            fetch(`/diagnosis/preset/${presetName}`)
                .then(response => response.json())
                .then(files => {
                    selectedFiles = files;
                    updateSelectedList();
                    updatePreview();
                    showToast(`پریست ${presetName} با ${files.length} فایل بارگذاری شد`, 'success');
                })
                .catch(error => {
                    console.error('Error loading preset:', error);
                    showToast('خطا در بارگذاری پریست', 'error');
                });
        }

        // آپدیت پیش‌نمایش
        async function updatePreview() {
            const previewContent = document.getElementById('previewContent');
            const previewStats = document.getElementById('previewStats');

            if (selectedFiles.length === 0) {
                previewContent.innerHTML = `
                    <div style="color: #94a3b8; text-align: center; padding: 40px 20px;">
                        <div style="font-size: 48px; margin-bottom: 15px;">✨</div>
                        <h4 style="margin: 0 0 10px 0; color: #cbd5e1;">پیش‌نمایش آماده است</h4>
                        <p style="margin: 0; font-size: 14px;">فایل‌ها را انتخاب کنید تا پیش‌نمایش اینجا نمایش داده شود</p>
                    </div>
                `;
                previewStats.textContent = '📝 ۰ فایل انتخاب شده';
                return;
            }

            previewStats.textContent = `⏳ در حال پردازش ${selectedFiles.length} فایل...`;

            try {
                const response = await fetch('/diagnosis/preview-merge', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ files: selectedFiles })
                });

                const data = await response.json();

                if (data.success) {
                    previewContent.innerHTML = data.preview;
                    previewStats.textContent = `📝 ${selectedFiles.length} فایل | ${formatFileSize(data.totalSize)}`;
                } else {
                    previewContent.innerHTML = `<div style="color: #f87171; padding: 20px;">${data.error}</div>`;
                    previewStats.textContent = '❌ خطا در پردازش';
                }
            } catch (error) {
                previewContent.innerHTML = `
                    <div style="color: #f87171; padding: 20px;">
                        <h4>خطا در بارگذاری پیش‌نمایش</h4>
                        <p>${error.message}</p>
                    </div>
                `;
                previewStats.textContent = '❌ خطا در ارتباط';
            }
        }

        // کپی به کلیپ‌بورد
        async function copyToClipboard() {
            if (selectedFiles.length === 0) {
                showToast('هیچ فایلی برای کپی وجود ندارد', 'warning');
                return;
            }

            try {
                const response = await fetch('/diagnosis/get-merged-text', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ files: selectedFiles })
                });

                const data = await response.json();

                if (navigator.clipboard) {
                    await navigator.clipboard.writeText(data.content);
                    showToast('محتوا با موفقیت کپی شد', 'success');

                    // افکت روی دکمه
                    const btn = document.getElementById('copyBtn');
                    btn.classList.add('pulse');
                    setTimeout(() => btn.classList.remove('pulse'), 500);
                } else {
                    // Fallback برای مرورگرهای قدیمی
                    const textArea = document.createElement('textarea');
                    textArea.value = data.content;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    showToast('محتوا کپی شد', 'success');
                }
            } catch (error) {
                console.error('Error copying to clipboard:', error);
                showToast('خطا در کپی کردن', 'error');
            }
        }

        // دانلود فایل ادغام شده
        async function downloadMerged() {
            if (selectedFiles.length === 0) {
                showToast('هیچ فایلی برای دانلود وجود ندارد', 'warning');
                return;
            }

            try {
                const response = await fetch('/diagnosis/download-merged', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ files: selectedFiles })
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `merged-${Date.now()}.txt`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);

                    showToast('فایل با موفقیت دانلود شد', 'success');
                } else {
                    showToast('خطا در دانلود فایل', 'error');
                }
            } catch (error) {
                console.error('Error downloading:', error);
                showToast('خطا در ارتباط', 'error');
            }
        }

        // اشتراک با AI (مثال)
        function shareWithAI() {
            if (selectedFiles.length === 0) {
                showToast('هیچ فایلی برای اشتراک وجود ندارد', 'warning');
                return;
            }

            // ایجاد لینک share
            const filePaths = selectedFiles.map(f => f.path).join(',');
            const shareUrl = `/diagnosis/share-with-ai?files=${encodeURIComponent(filePaths)}`;

            // باز کردن در پنجره جدید
            window.open(shareUrl, '_blank');

            showToast('در حال آماده‌سازی برای اشتراک با AI...', 'info');
        }

        // پاک کردن همه
        function clearAll() {
            if (selectedFiles.length === 0) {
                showToast('لیست فایل‌ها خالی است', 'info');
                return;
            }

            if (confirm(`آیا می‌خواهید ${selectedFiles.length} فایل حذف شوند؟`)) {
                selectedFiles = [];
                updateSelectedList();
                updatePreview();
                showToast('همه فایل‌ها حذف شدند', 'success');
            }
        }

        // مدیریت فایل‌های اخیر
        function loadRecentFiles() {
            const recentContainer = document.getElementById('recentFiles');
            recentContainer.innerHTML = '';

            recentFiles.slice(0, 6).forEach(file => {
                const recentFile = document.createElement('div');
                recentFile.className = 'recent-file';
                recentFile.innerHTML = `
                    <div class="file-name">${file.name}</div>
                    <div class="file-path">${file.path}</div>
                `;
                recentFile.onclick = () => addFileToList(file);
                recentContainer.appendChild(recentFile);
            });
        }

        function addToRecentFiles(file) {
            // حذف اگر قبلاً وجود دارد
            recentFiles = recentFiles.filter(f => f.path !== file.path);
            // اضافه کردن به ابتدا
            recentFiles.unshift(file);
            // محدود کردن به 20 آیتم
            recentFiles = recentFiles.slice(0, 20);
            // ذخیره در localStorage
            localStorage.setItem('recentFiles', JSON.stringify(recentFiles));
            loadRecentFiles();
        }

        function clearRecentFiles() {
            if (confirm('آیا می‌خواهید تاریخچه فایل‌های اخیر پاک شود؟')) {
                localStorage.removeItem('recentFiles');
                recentFiles = [];
                loadRecentFiles();
                showToast('تاریخچه پاک شد', 'success');
            }
        }

        // مودال تنظیمات پیشرفته
        function openAdvancedModal() {
            document.getElementById('advancedModal').classList.add('active');
        }

        function closeAdvancedModal() {
            document.getElementById('advancedModal').classList.remove('active');
        }

        function applyAdvancedSettings() {
            // اعمال تنظیمات
            const excludeDirs = document.getElementById('excludeDirs').value;
            const searchPattern = document.getElementById('searchPattern').value;
            const separatorType = document.getElementById('separatorType').value;
            const maxSize = document.getElementById('maxSize').value;

            // ذخیره تنظیمات
            localStorage.setItem('mergeSettings', JSON.stringify({
                excludeDirs,
                searchPattern,
                separatorType,
                maxSize
            }));

            showToast('تنظیمات اعمال شد', 'success');
            closeAdvancedModal();
        }

        // توابع کمکی
        function fetchFilesList() {
            // بارگذاری فهرست فایل‌ها از سرور
            fetch('/diagnosis/files-list')
                .then(response => response.json())
                .then(files => {
                    allFiles = files;
                })
                .catch(error => {
                    console.error('Error loading files list:', error);
                });
        }

        function getFileIcon(type) {
            const icons = {
                'php': '🐘',
                'blade': '🔪',
                'js': '📜',
                'css': '🎨',
                'json': '📋',
                'env': '🔐'
            };
            return icons[type] || '📄';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function showToast(message, type = 'info') {
            // ایجاد toast
            const toast = document.createElement('div');
            toast.className = `toast toast-${type} fade-in`;
            toast.innerHTML = `
                <span>${message}</span>
                <button onclick="this.parentElement.remove()">&times;</button>
            `;

            // استایل toast
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%)';
            toast.style.padding = '12px 24px';
            toast.style.borderRadius = '8px';
            toast.style.color = 'white';
            toast.style.fontWeight = '500';
            toast.style.zIndex = '9999';
            toast.style.display = 'flex';
            toast.style.alignItems = 'center';
            toast.style.gap = '15px';
            toast.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';

            // رنگ‌ها بر اساس نوع
            const colors = {
                'success': '#10b981',
                'error': '#ef4444',
                'warning': '#f59e0b',
                'info': '#3b82f6'
            };

            toast.style.background = colors[type] || colors.info;

            document.body.appendChild(toast);

            // حذف خودکار بعد از 3 ثانیه
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }

        // بستن toast با کلیک
        document.addEventListener('click', function(e) {
            if (e.target.className === 'toast') {
                e.target.remove();
            }
        });
    </script>
@endsection
