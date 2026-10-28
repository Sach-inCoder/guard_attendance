<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'full_name',
        'joining_date',
        'designation',
        'employment_status',
        'emergency_contact',
        'notes',
    ];
    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(GuardSiteAssignment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function attendanceAuditLogs(): HasMany
    {
        return $this->hasMany(AttendanceAuditLog::class);
    }
}
