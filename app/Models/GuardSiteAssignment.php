<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuardSiteAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'guard_id', 'site_id', 'shift_id', 'assignment_date',
        'starts_on', 'ends_on', 'scheduled_start_time', 'scheduled_end_time',
        'status', 'assigned_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'assignment_date' => 'date',
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(Guard::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
