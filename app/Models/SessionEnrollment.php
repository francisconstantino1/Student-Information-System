<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionEnrollment extends Model
{
    protected $fillable = [
        'user_id',
        'class_session_id',
        'attendance_code_id',
        'session_date',
        'enrolled_at',
        'resigned_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'enrolled_at' => 'datetime',
            'resigned_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function attendanceCode(): BelongsTo
    {
        return $this->belongsTo(AttendanceCode::class);
    }
}
