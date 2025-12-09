<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id',
        'semester',
        'academic_year',
        'prelim',
        'midterm',
        'prefinal',
        'final',
        'average',
        'remarks',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'prelim' => 'decimal:2',
            'midterm' => 'string', // Changed to string to support "INC"
            'prefinal' => 'decimal:2',
            'final' => 'string', // Changed to string to support "INC"
            'average' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the calculated remarks based on grades
     * In this system, 1 is the highest grade. Passing grade is 3.0 or below
     */
    public function getCalculatedRemarksAttribute(): string
    {
        // Check if either grade is INC
        $midterm = $this->midterm;
        $final = $this->final;
        
        if (($midterm !== null && strtoupper($midterm) === 'INC') || 
            ($final !== null && strtoupper($final) === 'INC')) {
            return 'Incomplete';
        }

        // Check if both grades are numeric and passing
        $numericGrades = [];
        if ($midterm !== null && $midterm !== '' && is_numeric($midterm)) {
            $numericGrades[] = (float) $midterm;
        }
        if ($final !== null && $final !== '' && is_numeric($final)) {
            $numericGrades[] = (float) $final;
        }

        if (count($numericGrades) === 2) {
            // Both grades are numeric - check if both are passing (<= 3.0)
            $allPassing = true;
            foreach ($numericGrades as $grade) {
                if ($grade > 3.0) {
                    $allPassing = false;
                    break;
                }
            }
            return $allPassing ? 'Passed' : 'Failed';
        } elseif (count($numericGrades) === 1) {
            // Only one grade - check if it's passing
            return $numericGrades[0] <= 3.0 ? 'Passed' : 'Failed';
        }

        return 'N/A';
    }
}
