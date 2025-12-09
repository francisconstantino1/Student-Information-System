<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Academic extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'subject_code',
        'subject_name',
        'schedule',
        'room',
        'instructor',
        'year_level',
        'semester',
        'units',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

