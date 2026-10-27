<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function guardProfile(): HasOne
    {
        return $this->hasOne(Guard::class);
    }

    public function assignedGuardSites(): HasMany
    {
        return $this->hasMany(GuardSiteAssignment::class, 'assigned_by');
    }

    public function createdQrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class, 'created_by');
    }

    public function createdAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'created_by');
    }

    public function updatedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'updated_by');
    }

    public function reviewedLeaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'reviewed_by');
    }

    public function createdHolidays(): HasMany
    {
        return $this->hasMany(Holiday::class, 'created_by');
    }

    public function attendanceAuditLogs(): HasMany
    {
        return $this->hasMany(AttendanceAuditLog::class, 'actor_user_id');
    }
}
