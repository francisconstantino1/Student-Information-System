<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPreference extends Model
{
    protected $fillable = [
        'user_id',
        'theme',
        'language',
        'notifications',
        'sidebar_mode',
        '2fa_enabled',
        'security_question',
        'security_answer',
        'bio',
        'profile_image',
    ];

    protected function casts(): array
    {
        return [
            'notifications' => 'array',
            '2fa_enabled' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
