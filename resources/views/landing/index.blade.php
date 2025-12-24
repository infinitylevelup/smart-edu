@extends('layouts.guest')

@section('title', 'سامانه هوشمند آموزش')

@section('content')
    {{-- ===== هدر ساده ===== --}}
    <header class="simple-header">
        <div class="container">
            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">⚡</div>
                <span>سامانه هوشمند آموزشی</span>
            </a>
        </div>
    </header>

    {{-- ===== بخش اصلی ===== --}}
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-card">
                <h1 class="hero-title">مسیر یادگیری شخصی</h1>

                <p class="hero-description">
                    وارد پنل کاربری خود شوید و از امکانات پیشرفته سامانه استفاده کنید.
                    تحلیل هوشمند، گزارش‌های دقیق و برنامه‌ریزی آموزشی متناسب با نیاز شما.
                </p>

<a href="#"
   class="btn-student js-open-auth"
   data-role="student"
   data-redirect="/dashboard/student">
  ورود دانش‌آموز
</a>

<a href="#"
   class="btn-teacher js-open-auth"
   data-role="teacher"
   data-redirect="/dashboard/teacher">
  ورود معلم
</a>


                <p class="hero-note">
                    هر کاربر مسیر آموزشی مخصوص به خود را تجربه می‌کند
                </p>
            </div>
        </div>
    </section>

    {{-- ===== امکانات سامانه ===== --}}
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">امکانات سامانه</h2>
                <p class="section-subtitle">هر آنچه برای یک تجربه آموزشی کامل نیاز دارید</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <div>
                        <h3 class="feature-title">آزمون‌های نمونه</h3>
                        <p class="feature-description">بانک سوالات طبقه‌بندی شده با پاسخ تشریحی و تحلیل عملکرد</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💎</div>
                    <div>
                        <h3 class="feature-title">اشتراک ویژه</h3>
                        <p class="feature-description">پلن‌های متنوع با امکانات پیشرفته برای نیازهای مختلف</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💡</div>
                    <div>
                        <h3 class="feature-title">پشتیبانی و راهنما</h3>
                        <p class="feature-description">مستندات کامل، ویدیوهای آموزشی و پشتیبانی آنلاین</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== پنل‌های نظارت و پیگیری ===== --}}
    <section class="monitoring-section" id="monitoring">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">پنل‌های نظارت و پیگیری</h2>
                <p class="section-subtitle">پیگیری و نظارت هوشمند بر روند آموزشی</p>
            </div>

            <div class="monitoring-grid">
                <div class="monitor-card">
                    <div class="monitor-icon">👨‍👩‍👧‍👦</div>
                    <div>
                        <h3 class="monitor-title">پنل اولیا</h3>
                        <p class="monitor-description">مشاهده گزارش‌های پیشرفت، نمرات و حضور و غیاب فرزندتان به صورت لحظه‌ای</p>
                    </div>
                </div>

                <div class="monitor-card">
                    <div class="monitor-icon">🧠</div>
                    <div>
                        <h3 class="monitor-title">پنل مشاور</h3>
                        <p class="monitor-description">تحلیل جامع عملکرد تحصیلی و ارائه برنامه‌ریزی تخصصی شخصی‌سازی شده</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ارتباط با ما ===== --}}
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">ارتباط با ما</h2>
                <p class="section-subtitle">ما اینجا هستیم تا به شما کمک کنیم</p>
            </div>

            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">💬</div>
                    <div>
                        <h3 class="contact-title">پشتیبانی آنلاین</h3>
                        <p class="contact-info">۲۴ ساعته و ۷ روز هفته پاسخگوی شما هستیم</p>
                    </div>
                    <div class="contact-buttons">
                        <a href="#support" class="contact-btn support">شروع گفتگو</a>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">🌐</div>
                    <div>
                        <h3 class="contact-title">فضای اجتماعی</h3>
                        <p class="contact-info">در شبکه‌های اجتماعی ما را دنبال کنید</p>
                    </div>

                    <div class="social-icons-grid">
                        <a href="https://shad.ir" target="_blank" class="social-icon-btn shad" title="شاد">
                            <i class="fas fa-graduation-cap"></i>
                        </a>
                        <a href="https://eitaa.com" target="_blank" class="social-icon-btn eitaa" title="ایتا">
                            <i class="fas fa-comment-alt"></i>
                        </a>
                        <a href="https://bale.ai" target="_blank" class="social-icon-btn bale" title="بله">
                            <i class="fas fa-comment"></i>
                        </a>
                        <a href="https://telegram.org" target="_blank" class="social-icon-btn telegram" title="تلگرام">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="social-icon-btn instagram" title="اینستاگرام">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://whatsapp.com" target="_blank" class="social-icon-btn whatsapp" title="واتساپ">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://youtube.com" target="_blank" class="social-icon-btn youtube" title="یوتیوب">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://aparat.com" target="_blank" class="social-icon-btn aparat" title="آپارات">
                            <i class="fas fa-video"></i>
                        </a>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <div>
                        <h3 class="contact-title">تماس با ما</h3>
                        <p class="contact-info">تلفن: ۰۲۱-۰۰۰۰۰۰۰۰<br>ایمیل: info@example.com</p>
                    </div>
                    <div class="contact-buttons">
                        <a href="tel:+982100000000" class="contact-btn phone">تماس تلفنی</a>
                        <a href="mailto:info@example.com" class="contact-btn email">ارسال ایمیل</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== فوتر ===== --}}
    <footer class="simple-footer">
        <div class="container">
            <div class="copyright">
                © ۱۴۰۳ سامانه هوشمند آموزش. تمامی حقوق محفوظ است.
            </div>
        </div>
    </footer>
@endsection
