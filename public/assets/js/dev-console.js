// ========== dev-console.js ==========
// فایل JavaScript کنسول توسعه SmartEdu

// متغیرهای global
let commandHistory = [];
let selectedExamId = null;

// توابع اصلی
function runCommand(command, params = {}) {
    console.log('🎯 اجرای دستور:', command, params);
    
    // نمایش در ترمینال
    addOutput(`$ ${command}`, 'command');
    
    // آپدیت وضعیت
    document.getElementById('execStatus').textContent = 'در حال اجرا...';
    document.getElementById('execStatus').className = 'text-warning';
    
    fetch('/dev/run-command', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            command: command,
            params: params 
        })
    })
    .then(async response => {
        const result = await response.json();
        
        // آپدیت زمان اجرا
        document.getElementById('execTime').textContent = `${result.execution_time || 0}s`;
        
        if (result.success) {
            addOutput(`✅ ${result.message}`, 'success');
            document.getElementById('execStatus').textContent = 'موفق';
            document.getElementById('execStatus').className = 'text-success';
            
            // آپدیت JSON
            document.getElementById('jsonOutput').textContent = JSON.stringify(result, null, 2);
        } else {
            addOutput(`❌ ${result.message}`, 'error');
            document.getElementById('execStatus').textContent = 'خطا';
            document.getElementById('execStatus').className = 'text-danger';
        }
    })
    .catch(error => {
        addOutput(`❌ خطا در ارتباط: ${error.message}`, 'error');
        document.getElementById('execStatus').textContent = 'خطا شبکه';
        document.getElementById('execStatus').className = 'text-danger';
    });
}

function runQuickCommand(command, params = {}) {
    document.getElementById('commandInput').value = command;
    executeCommand(params);
}

function runArtisan(cmd) {
    runCommand('artisan', {cmd: cmd});
}

function executeCommand(params = null) {
    const input = document.getElementById('commandInput');
    const command = input.value.trim();
    
    if (!command) {
        addOutput('❌ لطفاً یک دستور وارد کنید', 'error');
        return;
    }
    
    runCommand(command, params || {});
    input.value = '';
}

function addOutput(text, type = 'info') {
    const output = document.getElementById('terminalOutput');
    if (!output) return;
    
    const time = new Date().toLocaleTimeString('fa-IR');
    const entry = document.createElement('div');
    entry.className = `log-entry log-${type}`;
    entry.innerHTML = `<span class="timestamp">[${time}]</span> <span class="result-text">${text}</span>`;
    
    output.appendChild(entry);
    output.scrollTop = output.scrollHeight;
}

function clearConsole() {
    const output = document.getElementById('terminalOutput');
    if (output) output.innerHTML = '';
    addOutput('کنسول پاک شد', 'info');
}

function clearHistory() {
    if (confirm('آیا می‌خواهید تاریخچه پاک شود؟')) {
        localStorage.removeItem('devConsoleHistory');
        document.getElementById('commandHistory').innerHTML = '';
        alert('تاریخچه پاک شد');
    }
}

function createTestExam() {
    const type = document.getElementById('examTypeSelect')?.value || 'public';
    const title = prompt('عنوان آزمون:', `آزمون تستی ${new Date().toLocaleTimeString('fa-IR')}`);
    
    if (title) {
        runCommand('create_test_exam', {type: type, title: title});
    }
}

function loadSampleCode() {
    const phpCode = `// کد PHP نمونه
public function testFunction()
{
    return ['success' => true];
}`;
    
    const jsCode = `// کد JavaScript نمونه
async function test() {
    const response = await fetch('/test');
    return response.json();
}`;
    
    document.getElementById('phpOutput').textContent = phpCode;
    document.getElementById('jsOutput').textContent = jsCode;
}

function copyResults() {
    const text = document.getElementById('jsonOutput').textContent;
    navigator.clipboard.writeText(text);
    alert('✅ نتایج کپی شد!');
}

function exportResults() {
    const data = document.getElementById('jsonOutput').textContent;
    const blob = new Blob([data], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = `dev-console-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    alert('✅ فایل JSON دانلود شد');
}

function downloadLogs() {
    alert('📥 دانلود لاگ‌ها - به زودی');
}

// راه‌اندازی اولیه
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 کنسول توسعه SmartEdu بارگذاری شد');
    
    // تست اولیه
    runCommand('ping');
    
    // رویدادهای صفحه‌کلید
    document.getElementById('commandInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') executeCommand();
    });
});

// قرار دادن توابع در global scope
window.runCommand = runCommand;
window.runQuickCommand = runQuickCommand;
window.runArtisan = runArtisan;
window.executeCommand = executeCommand;
window.clearConsole = clearConsole;
window.clearHistory = clearHistory;
window.createTestExam = createTestExam;
window.loadSampleCode = loadSampleCode;
window.copyResults = copyResults;
window.exportResults = exportResults;
window.downloadLogs = downloadLogs;
// در انتهای فایل اضافه کنید:
window.runQuickCommand = function(command, params = {}) {
    console.log('🚀 runQuickCommand called:', command);
    runCommand(command, params);
};

window.runArtisan = function(cmd) {
    runCommand('artisan', {cmd: cmd});
};

window.createTestExam = function() {
    const type = document.getElementById('examTypeSelect')?.value || 'public';
    const title = prompt('عنوان آزمون:');
    if (title) runCommand('create_test_exam', {type: type, title: title});
};

// بقیه توابع...