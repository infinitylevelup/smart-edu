<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <i class="fas fa-graduation-cap"></i>
            سامانه هوشمند آموزش
        </a>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#authModal">
                <i class="fas fa-sign-in-alt ms-1"></i> ورود / ثبت‌نام
            </button>
            <button class="btn btn-light text-dark fw-bold"
                onclick="document.getElementById('pricing').scrollIntoView({behavior:'smooth'})">
                مشاهده پلن‌ها
            </button>
        </div>
    </div>
</nav>
