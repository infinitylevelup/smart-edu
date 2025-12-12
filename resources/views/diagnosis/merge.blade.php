@extends('diagnosis.layout')

@section('title', 'ادغام هوشمند فایل‌ها')

@section('breadcrumb', 'ادغام هوشمند')

@section('content')
<style>
    .merge-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .merge-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .merge-header h1 {
        color: #7c3aed;
        margin-bottom: 10px;
    }

    .merge-header p {
        color: #6b7280;
        font-size: 18px;
    }

    .merge-grid {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 30px;
        height: 70vh;
    }

    @media (max-width: 1024px) {
        .merge-grid {
            grid-template-columns: 1fr;
            height: auto;
        }
    }

    .file-selector {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }

    .preview-panel {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
    }

    .search-box {
        position: relative;
        margin-bottom: 25px;
    }

    .search-input {
        width: 100%;
        padding: 14px 20px 14px 45px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .search-results {
        position: absolute;
        width: 100%;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 100;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        display: none;
    }

    .search-result-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.2s;
    }

    .search-result-item:hover {
        background: #f9fafb;
    }

    .search-result-item .file-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .search-result-item .file-info {
        flex: 1;
    }

    .search-result-item .file-name {
        font-weight: 600;
        margin-bottom: 3px;
        color: #1f2937;
    }

    .search-result-item .file-path {
        font-size: 12px;
        color: #6b7280;
        font-family: monospace;
    }

    .selected-files {
        flex: 1;
        overflow-y: auto;
        margin-top: 20px;
        padding: 15px;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        min-height: 200px;
    }

    .selected-file {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .selected-file-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .selected-file-actions {
        display: flex;
        gap: 5px;
    }

    .btn-icon {
        background: none;
        border: none;
        padding: 5px;
        cursor: pointer;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .btn-icon:hover {
        background: #f3f4f6;
    }

    .presets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
        margin: 25px 0;
    }

    .preset-card {
        background: #f9fafb;
        border: 2px solid transparent;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .preset-card:hover {
        border-color: #8b5cf6;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .preset-icon {
        font-size: 30px;
        margin-bottom: 10px;
    }

    .preset-name {
        font-weight: 600;
        margin-bottom: 5px;
        color: #1f2937;
    }

    .preset-desc {
        font-size: 12px;
        color: #6b7280;
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .preview-stats {
        background: #8b5cf6;
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
        border-radius: 8px;
        padding: 20px;
        font-family: 'Monaco', 'Consolas', monospace;
        color: #e2e8f0;
        white-space: pre-wrap;
        line-height: 1.5;
        font-size: 13px;
    }

    .file-header {
        color: #60a5fa;
        margin: 20px 0 10px 0;
        padding-bottom: 5px;
        border-bottom: 1px solid #334155;
    }

    .controls {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .control-btn {
        flex: 1;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-copy {
        background: #10b981;
        color: white;
    }

    .btn-copy:hover {
        background: #0da271;
    }

    .btn-download {
        background: #8b5cf6;
        color: white;
    }

    .btn-download:hover {
        background: #7c3aed;
    }

    .btn-clear {
        background: #ef4444;
        color: white;
    }

    .btn-clear:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 60px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

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
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .recent-file {
        background: #f3f4f6;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .recent-file:hover {
        border-color: #8b5cf6;
        background: white;
    }

    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 24px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .toast-success {
        background: #10b981;
    }

    .toast-error {
        background: #ef4444;
    }

    .toast-info {
        background: #3b82f6;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<div class="merge-container">
    <div class="merge-header">
        <h1>🧠 MergeMaster - ادغام هوشمند فایل‌ها</h1>
        <p>انتخاب سریع فایل‌های مشکل‌دار و آماده‌سازی برای بازبینی هوش مصنوعی</p>
    </div>

    <div class="merge-grid">
        <!-- سمت چپ: انتخاب فایل‌ها -->
        <div class="file-selector">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text"
                       class="search-input"
                       placeholder="جستجوی فایل... (مثال: UserController، login.blade.php)"
                       id="searchInput"
                       autocomplete="off">
                <div class="search-results" id="searchResults"></div>
            </div>

            <h3 style="margin: 25px 0 15px 0; color: #1f2937;">
                فایل‌های انتخاب شده
                <span id="selectedCount" style="background: #8b5cf6; color: white; padding: 2px 10px; border-radius: 12px; font-size: 12px;">0</span>
            </h3>

            <div class="selected-files" id="selectedFiles">
                <div class="empty-state" id="emptyState">
                    <div class="empty-state-icon">📁</div>
                    <h4 style="margin: 0 0 10px 0; color: #6b7280;">هنوز فایلی انتخاب نکرده‌اید</h4>
                    <p style="margin: 0; font-size: 14px;">فایل‌ها را جستجو کنید یا از پریست‌های زیر استفاده کنید</p>
                </div>
            </div>

            <h3 style="margin: 30px 0 15px 0; color: #1f2937;">🚀 پریست‌های سریع</h3>
            <div class="presets-grid">
                @foreach($presets as $key => $preset)
                <div class="preset-card" onclick="loadPreset('{{ $key }}')">
                    <div class="preset-icon">{{ $preset['icon'] }}</div>
                    <div class="preset-name">{{ $preset['name'] }}</div>
                    <div class="preset-desc">{{ $preset['description'] }}</div>
                </div>
                @endforeach
            </div>

            @if(!empty($recentFiles))
            <div class="recent-files">
                <div class="recent-header">
                    <h4 style="margin: 0; color: #1f2937;">🕐 فایل‌های اخیر</h4>
                    <button onclick="clearRecent()" style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 12px;">
                        پاک کردن
                    </button>
                </div>
                <div class="recent-list">
                    @foreach($recentFiles as $file)
                    <div class="recent-file" onclick="addFile('{{ $file }}')">
                        {{ basename($file) }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- سمت راست: پیش‌نمایش -->
        <div class="preview-panel">
            <div class="preview-header">
                <h2 style="margin: 0; color: #1f2937;">👁️ پیش‌نمایش ادغام</h2>
                <div class="preview-stats" id="previewStats">۰ فایل</div>
            </div>

            <div class="preview-content" id="previewContent">
                <div class="empty-state">
                    <div class="empty-state-icon">✨</div>
                    <h4 style="margin: 0 0 10px 0; color: #cbd5e1;">پیش‌نمایش آماده است</h4>
                    <p style="margin: 0; font-size: 14px;">فایل‌ها را انتخاب کنید تا پیش‌نمایش اینجا نمایش داده شود</p>
                </div>
            </div>

            <div class="controls">
                <button class="control-btn btn-copy" onclick="copyToClipboard()" id="copyBtn">
                    📋 کپی محتوا
                </button>
                <button class="control-btn btn-download" onclick="downloadMerged()">
                    ⬇️ دانلود فایل
                </button>
                <button class="control-btn btn-clear" onclick="clearAll()">
                    🗑️ پاک کردن همه
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedFiles = [];
    let searchTimeout = null;

    // جستجوی فایل‌ها
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);

        if (e.target.value.length < 2) {
            hideResults();
            return;
        }

        searchTimeout = setTimeout(() => {
            searchFiles(e.target.value);
        }, 300);
    });

    async function searchFiles(query) {
        try {
            const response = await fetch(`/diagnosis/merge/search?q=${encodeURIComponent(query)}`);
            const results = await response.json();

            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = '';

            if (results.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="search-result-item" style="color: #9ca3af; cursor: default;">
                        <div class="file-icon">🔍</div>
                        <div class="file-info">
                            <div class="file-name">نتیجه‌ای یافت نشد</div>
                            <div class="file-path">سعی کنید نام فایل را دقیق‌تر وارد کنید</div>
                        </div>
                    </div>
                `;
            } else {
                results.forEach(file => {
                    const item = document.createElement('div');
                    item.className = 'search-result-item';
                    item.innerHTML = `
                        <div class="file-icon">${file.icon}</div>
                        <div class="file-info">
                            <div class="file-name">${file.name}</div>
                            <div class="file-path">${file.path}</div>
                        </div>
                    `;
                    item.onclick = () => {
                        addFile(file.path);
                        hideResults();
                        document.getElementById('searchInput').value = '';
                    };
                    resultsContainer.appendChild(item);
                });
            }

            showResults();
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    function showResults() {
        document.getElementById('searchResults').style.display = 'block';
    }

    function hideResults() {
        document.getElementById('searchResults').style.display = 'none';
    }

    // اضافه کردن فایل
    function addFile(filePath) {
        if (selectedFiles.includes(filePath)) {
            showToast('این فایل قبلاً اضافه شده است', 'info');
            return;
        }

        selectedFiles.push(filePath);
        updateSelectedList();
        updatePreview();
        showToast('فایل اضافه شد', 'success');
    }

    // حذف فایل
    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateSelectedList();
        updatePreview();
    }

    // آپدیت لیست انتخاب شده
    function updateSelectedList() {
        const container = document.getElementById('selectedFiles');
        const emptyState = document.getElementById('emptyState');
        const countBadge = document.getElementById('selectedCount');

        countBadge.textContent = selectedFiles.length;

        if (selectedFiles.length === 0) {
            container.innerHTML = '';
            container.appendChild(emptyState);
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        container.innerHTML = '';

        selectedFiles.forEach((filePath, index) => {
            const fileName = filePath.split('/').pop();
            const fileExt = fileName.split('.').pop();

            const fileDiv = document.createElement('div');
            fileDiv.className = 'selected-file';
            fileDiv.innerHTML = `
                <div class="selected-file-info">
                    <span style="font-size: 20px;">${getFileIcon(filePath)}</span>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 3px;">${fileName}</div>
                        <div style="font-size: 12px; color: #6b7280; font-family: monospace;">${filePath}</div>
                    </div>
                </div>
                <div class="selected-file-actions">
                    <button class="btn-icon" onclick="removeFile(${index})" title="حذف">
                        ❌
                    </button>
                </div>
            `;
            container.appendChild(fileDiv);
        });
    }

    // لود پریست
    async function loadPreset(presetName) {
        try {
            showToast('در حال بارگذاری پریست...', 'info');

            const response = await fetch(`/diagnosis/merge/preset/${presetName}`);
            const files = await response.json();

            selectedFiles = files;
            updateSelectedList();
            updatePreview();

            showToast(`پریست با ${files.length} فایل بارگذاری شد`, 'success');
        } catch (error) {
            console.error('Preset error:', error);
            showToast('خطا در بارگذاری پریست', 'error');
        }
    }

    // آپدیت پیش‌نمایش
    async function updatePreview() {
        const previewContent = document.getElementById('previewContent');
        const previewStats = document.getElementById('previewStats');

        if (selectedFiles.length === 0) {
            previewContent.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">✨</div>
                    <h4 style="margin: 0 0 10px 0; color: #cbd5e1;">پیش‌نمایش آماده است</h4>
                    <p style="margin: 0; font-size: 14px;">فایل‌ها را انتخاب کنید تا پیش‌نمایش اینجا نمایش داده شود</p>
                </div>
            `;
            previewStats.textContent = '۰ فایل';
            return;
        }

        previewStats.textContent = `⏳ در حال پردازش...`;

        try {
            const response = await fetch('/diagnosis/merge/preview', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ files: selectedFiles })
            });

            const data = await response.json();

            let html = '';
            let content = data.content;
            let lines = content.split('\n');
            let inFileHeader = false;

            lines.forEach(line => {
                if (line.startsWith('==================== FILE:')) {
                    if (inFileHeader) {
                        html += '</div>';
                    }
                    html += `<div class="file-header">${line.replace(/=/g, '')}</div><div>`;
                    inFileHeader = true;
                } else {
                    // Escape HTML and preserve spaces
                    const escapedLine = line
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;')
                        .replace(/ /g, '&nbsp;')
                        .replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;');

                    html += escapedLine + '<br>';
                }
            });

            if (inFileHeader) {
                html += '</div>';
            }

            previewContent.innerHTML = html;
            previewStats.textContent = `${data.fileCount} فایل | ${data.totalSize}`;

            // اسکرول به بالا
            previewContent.scrollTop = 0;
        } catch (error) {
            console.error('Preview error:', error);
            previewContent.innerHTML = `<div style="color: #f87171; padding: 20px;">خطا در بارگذاری پیش‌نمایش: ${error.message}</div>`;
            previewStats.textContent = '❌ خطا';
        }
    }

    // کپی به کلیپ‌بورد
    async function copyToClipboard() {
        if (selectedFiles.length === 0) {
            showToast('هیچ فایلی برای کپی وجود ندارد', 'info');
            return;
        }

        try {
            const response = await fetch('/diagnosis/merge/preview', {
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
                btn.innerHTML = '✅ کپی شد!';
                setTimeout(() => {
                    btn.innerHTML = '📋 کپی محتوا';
                }, 2000);
            } else {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = data.content;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showToast('محتوا کپی شد', 'success');
            }
        } catch (error) {
            console.error('Copy error:', error);
            showToast('خطا در کپی کردن', 'error');
        }
    }

    // دانلود فایل
    async function downloadMerged() {
        if (selectedFiles.length === 0) {
            showToast('هیچ فایلی برای دانلود وجود ندارد', 'info');
            return;
        }

        try {
            const response = await fetch('/diagnosis/merge/download', {
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
            console.error('Download error:', error);
            showToast('خطا در ارتباط', 'error');
        }
    }

    // پاک کردن همه
    function clearAll() {
        if (selectedFiles.length === 0) return;

        if (confirm(`آیا می‌خواهید ${selectedFiles.length} فایل حذف شوند؟`)) {
            selectedFiles = [];
            updateSelectedList();
            updatePreview();
            showToast('همه فایل‌ها حذف شدند', 'success');
        }
    }

    // پاک کردن تاریخچه
    function clearRecent() {
        fetch('/diagnosis/merge/clear-recent', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            window.location.reload();
        });
    }

    // توابع کمکی
    function getFileIcon(filePath) {
        if (filePath.includes('Controller')) return '🎮';
        if (filePath.endsWith('.blade.php')) return '🔪';
        if (filePath.endsWith('.php')) return '🐘';
        if (filePath.endsWith('.js')) return '📜';
        if (filePath.endsWith('.css')) return '🎨';
        if (filePath.endsWith('.json')) return '📋';
        return '📄';
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // رویدادهای کلیک
    document.addEventListener('click', function(e) {
        // بستن نتایج جستجو با کلیک بیرون
        if (!e.target.closest('.search-box')) {
            hideResults();
        }
    });

    // کلیدهای میانبر
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + C برای کپی
        if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
            e.preventDefault();
            copyToClipboard();
        }

        // Esc برای بستن نتایج جستجو
        if (e.key === 'Escape') {
            hideResults();
        }
    });
</script>
@endsection
