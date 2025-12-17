<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    // ⚠️ توجه: اگر id اتوایکریمنت INT است، این تنظیمات درست است:
    // public $incrementing = true; // پیش‌فرض (اتوایکریمنت)
    // protected $keyType = 'int';  // پیش‌فرض

    // اگر migration جدول topics از UUID برای id استفاده می‌کند،
    // تنظیمات زیر درست هستند:
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'subject_id',
        'title_fa',
        'sort_order',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            // اگر id از نوع UUID است و ستونی به نام id دارید (نه uuid)
            if (empty($m->id) && $m->incrementing === false) {
                $m->id = (string) Str::uuid();
            }

            // اگر ستون uuid جداگانه دارید
            if (empty($m->uuid) && !empty($m->getAttributes()['uuid'])) {
                $m->uuid = (string) Str::uuid();
            }
        });
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
