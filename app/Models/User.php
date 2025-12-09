<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'name',
        'email',
        'personal_email',
        'role',
        'password',
        'address',
        'birthday',
        'contact_number',
        'gender',
        'profile_image',
        'guardian_name',
        'guardian_contact',
        'course',
        'year_level',
        'section_id',
        'monthly_tuition',
        'position',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
        ];
    }

    // Relationships
    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function preference()
    {
        return $this->hasOne(StudentPreference::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function requests()
    {
        return $this->hasMany(StudentRequest::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function latestEnrollment()
    {
        return $this->hasOne(Enrollment::class)->latestOfMany();
    }

    public function classSessions()
    {
        return $this->belongsToMany(ClassSession::class, 'user_class_session')
            ->withPivot('enrolled_date')
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(StudentNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(StudentNotification::class)->where('is_read', false);
    }

    public function sessionEnrollments()
    {
        return $this->hasMany(SessionEnrollment::class);
    }

    public function activeSessionEnrollments()
    {
        return $this->hasMany(SessionEnrollment::class)->where('is_active', true);
    }
}
