<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentId extends Model
{
    protected $fillable = [
        'student_id',
        'status',
        'assigned_to',
        'created_by',
        'assigned_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function markAsUsed(int $userId): void
    {
        $this->update([
            'status' => 'used',
            'assigned_to' => $userId,
            'used_at' => now(),
        ]);
    }
}
