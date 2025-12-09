<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'address',
        'email',
        'birthday',
        'gender',
        'previous_school',
        'course_selected',
        'year_level',
        'guardian_name',
        'guardian_contact',
        'status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'birthday' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
