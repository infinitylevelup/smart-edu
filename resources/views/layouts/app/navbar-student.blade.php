<header class="main-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="student-avatar d-none d-md-flex">
                    <span>{{ mb_substr(auth()->user()->name ?? 'آ', 0, 1, 'UTF-8') }}</span>
                </div>
                <div class="me-3">
                    <h4 class="mb-0">سلام {{ (auth()->user()->name ? explode(' ', auth()->user()->name)[0] : 'کاربر') }}! 👋</h4>
                    <p class="text-muted mb-0">امروز {{ \Illuminate\Support\Carbon::now()->locale('fa')->isoFormat('YYYY/MM/DD') }}</p>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="me-3">
                    <span class="user-plan">پلن ۲ (کنکوری)</span>
                </div>

                <div class="position-relative">
                    <i class="bi bi-bell-fill fs-4 text-primary"></i>
                    <span class="notification-badge">۵</span>
                </div>
            </div>
        </div>
    </div>
</header>
