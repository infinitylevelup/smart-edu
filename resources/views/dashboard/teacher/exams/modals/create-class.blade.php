<!-- Modal ساخت کلاس جدید -->
<div class="modal fade" id="createClassModal" tabindex="-1" aria-labelledby="createClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClassModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    ساخت کلاس جدید
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createClassForm" action="{{ route('teacher.classes.store') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="classroom_type" id="modal_classroom_type" value="single">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عنوان کلاس *</label>
                            <input type="text" name="title" class="form-control" 
                                   placeholder="مثال: کلاس ریاضی پایه هفتم" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع کلاس</label>
                            <div class="form-control" style="padding: 0.75rem;">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" 
                                           id="typeSingle" value="single" checked>
                                    <label class="form-check-label" for="typeSingle">
                                        تک‌درس
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" 
                                           id="typeComprehensive" value="comprehensive">
                                    <label class="form-check-label" for="typeComprehensive">
                                        جامع
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">توضیحات</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="توضیحات درباره کلاس..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        پس از ایجاد کلاس، می‌توانید از آن در آزمون‌های کلاسی استفاده کنید.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="submit" form="createClassForm" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>
                    ایجاد کلاس
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // وقتی مدال باز می‌شود
    $('#createClassModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const classType = button.data('class-type') || 'single';
        
        // تنظیم نوع کلاس
        $('#modal_classroom_type').val(classType);
        
        // انتخاب radio button مربوطه
        if (classType === 'single') {
            $('#typeSingle').prop('checked', true);
        } else {
            $('#typeComprehensive').prop('checked', true);
        }
        
        // به‌روزرسانی عنوان
        const title = classType === 'single' ? 'ساخت کلاس تک‌درس' : 'ساخت کلاس جامع';
        $('#createClassModalLabel').html(`<i class="bi bi-plus-circle me-2"></i>${title}`);
    });
    
    // وقتی فرم ارسال می‌شود
    $('#createClassForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = form.serialize();
        
        // ارسال درخواست AJAX
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-1"></span>در حال ایجاد...');
            },
            success: function(response) {
                if (response.success) {
                    // بستن مدال
                    $('#createClassModal').modal('hide');
                    
                    // نمایش پیام موفقیت
                    alert('کلاس با موفقیت ایجاد شد!');
                    
                    // ریدایرکت به صفحه کلاس‌ها یا رفرش صفحه
                    window.location.reload();
                } else {
                    alert(response.message || 'خطا در ایجاد کلاس');
                }
            },
            error: function(xhr) {
                let errorMessage = 'خطا در ایجاد کلاس';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                alert(errorMessage);
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false)
                    .html('<i class="bi bi-check-circle me-1"></i>ایجاد کلاس');
            }
        });
    });
});
</script>