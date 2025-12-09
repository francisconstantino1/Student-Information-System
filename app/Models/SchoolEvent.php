<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'date',
        'type',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];
}


