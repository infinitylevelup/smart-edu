@extends('diagnosis.layout')

@section('title', 'ساختار پروژه')

@section('breadcrumb', 'ساختار پروژه / فهرست پوشه‌ها')

@section('content')
    <div class="card">
        <h2 style="margin-top: 0;">📁 ساختار پروژه</h2>
        <p>فولدرهای موجود در ریشه پروژه:</p>

        <!-- دکمه ادغام فایل‌ها -->
        <div style="margin: 20px 0;">
            <button onclick="openModal('mergeModal')" class="btn btn-primary">
                📋 ادغام فایل‌ها (برای AI)
            </button>

            <a href="{{ route('diagnosis.structure.appTree') }}" class="btn"
               style="background: #6b7280; color: white; margin-right: 10px;">
                🌳 نمایش درخت app/
            </a>
        </div>

        <!-- جدول پوشه‌ها -->
        <table class="table">
            <thead>
                <tr>
                    <th>نام پوشه</th>
                    <th>نوع</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dirs as $dir)
                    <tr>
                        <td>
                            <strong>{{ $dir }}</strong>
                            @if($dir === 'vendor')
                                <small style="color: #9ca3af;">(حذف شده از لیست)</small>
                            @endif
                        </td>
                        <td>
                            @if(in_array($dir, $laravelKnown))
                                <span style="color: var(--secondary-color);">✨ استاندارد Laravel</span>
                            @else
                                <span style="color: #6b7280;">📂 پوشه معمولی</span>
                            @endif
                        </td>
                        <td>
                            @if($dir !== 'vendor')
                                <button onclick="addToMerge('{{ $dir }}/')" class="btn"
                                        style="background: #f3f4f6; padding: 5px 10px; font-size: 12px;">
                                    ➕ افزودن به ادغام
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- نمایش نتایج ادغام -->
<!-- نمایش نتایج ادغام -->
@if(session('mergedText'))
    <div class="card" style="margin-top: 30px; animation: fadeIn 0.5s;">
        <h3>✅ نتایج ادغام</h3>

        <p><strong>{{ count(session('mergedFiles', [])) }} فایل ادغام شده:</strong></p>
        <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin: 15px 0; max-height: 200px; overflow-y: auto;">
            @foreach(session('mergedFiles', []) as $file)
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; padding: 8px; background: white; border-radius: 6px;">
                    <span style="color: #6b7280;">📄</span>
                    <code style="flex: 1; font-size: 12px;">{{ $file }}</code>
                    <a href="{{ route('diagnosis.file', ['path' => $file]) }}"
                       target="_blank"
                       style="color: #3b82f6; text-decoration: none; font-size: 12px;">
                        مشاهده
                    </a>
                </div>
            @endforeach
        </div>

        <div class="form-group">
            <label class="form-label">متن ادغام شده (آماده کپی برای AI):</label>
            <textarea id="mergedText" class="form-control" rows="15" readonly
                      style="font-family: 'Monaco', 'Consolas', monospace; font-size: 13px; direction: ltr;">{{ session('mergedText') }}</textarea>
            <div style="margin-top: 15px; display: flex; gap: 10px; align-items: center;">
                <button onclick="copyToClipboard(document.getElementById('mergedText').value, 'copyBtn')"
                        class="btn btn-success" id="copyBtn" style="flex: 1;">
                    📋 کپی متن
                </button>

                <form method="POST" action="{{ route('diagnosis.clearSession') }}" style="display: inline; flex: 1;">
                    @csrf
                    <button type="submit" class="btn"
                            style="background: #f3f4f6; width: 100%; color: #6b7280;"
                            onclick="return confirm('آیا می‌خواهید نتایج پاک شوند؟')">
                        🗑️ پاک‌کردن
                    </button>
                </form>

                <a href="data:text/plain;charset=utf-8,{{ urlencode(session('mergedText')) }}"
                   download="merged-{{ now()->format('Y-m-d-His') }}.txt"
                   class="btn" style="background: #8b5cf6; color: white; flex: 1; text-align: center; text-decoration: none;">
                    💾 دانلود
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text, elementId) {
            navigator.clipboard.writeText(text).then(function() {
                const btn = document.getElementById(elementId);
                const originalText = btn.innerHTML;
                btn.innerHTML = '✅ کپی شد!';
                btn.style.background = '#10b981';
                setTimeout(function() {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                }, 2000);

                // نمایش پیام
                showToast('متن با موفقیت کپی شد', 'success');
            }).catch(function(err) {
                // Fallback برای مرورگرهای قدیمی
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('متن کپی شد', 'success');
            });
        }

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 24px;
                background: ${type === 'success' ? '#10b981' : '#3b82f6'};
                color: white;
                border-radius: 8px;
                font-weight: 500;
                z-index: 1000;
                animation: slideIn 0.3s ease-out;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            `;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // اضافه کردن استایل انیمیشن
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
@endif

    <!-- مودال ادغام -->
    <div class="modal" id="mergeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin: 0;">📋 ادغام فایل‌ها</h3>
                <button onclick="closeModal('mergeModal')" class="modal-close">&times;</button>
            </div>

            <form method="POST" action="{{ route('diagnosis.structure.mergeManual') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">مسیر فایل‌ها (هر مسیر در یک خط):</label>
                    <textarea name="paths" class="form-control" rows="12"
                              placeholder="مثال:
app/Http/Controllers/HomeController.php
resources/views/home.blade.php
routes/web.php
config/app.php">{{ old('paths', session('rawPaths', '')) }}</textarea>
                    <small style="color: #6b7280; display: block; margin-top: 5px;">
                        💡 می‌توانید از لیست بالا فایل‌ها را کپی کنید یا مسیرهای دلخواه را وارد نمایید.
                    </small>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">ادغام و نمایش</button>
                    <button type="button" onclick="closeModal('mergeModal')" class="btn"
                            style="background: #f3f4f6;">انصراف</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addToMerge(path) {
            const textarea = document.querySelector('textarea[name="paths"]');
            const currentValue = textarea.value.trim();
            textarea.value = currentValue + (currentValue ? '\n' : '') + path;
            openModal('mergeModal');
        }
    </script>
@endsection
