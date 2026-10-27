<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;

    public const STATUSES = [
        'present', 'absent', 'half_day', 'weekly_off', 'holiday', 'late',
    ];

    protected $fillable = [
        'guard_id', 'guard_site_assignment_id', 'site_id', 'attendance_date',
        'check_in_at', 'check_out_at',
        'check_in_latitude', 'check_in_longitude', 'check_in_accuracy_m',
        'check_out_latitude', 'check_out_longitude', 'check_out_accuracy_m',
        'worked_minutes', 'status', 'check_in_method', 'check_out_method',
        'remarks', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'check_in_latitude' => 'decimal:7',
            'check_in_longitude' => 'decimal:7',
            'check_out_latitude' => 'decimal:7',
            'check_out_longitude' => 'decimal:7',
            'check_in_accuracy_m' => 'integer',
            'check_out_accuracy_m' => 'integer',
            'worked_minutes' => 'integer',
        ];
    }

    public function guard(): BelongsTo
    {
        return $this->belongsTo(Guard::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(GuardSiteAssignment::class, 'guard_site_assignment_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AttendanceAuditLog::class);
    }
}
