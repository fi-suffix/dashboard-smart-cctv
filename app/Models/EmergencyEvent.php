<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_uuid',
        'camera_id',
        'type',
        'severity',
        'confidence',
        'snapshot_path',
        'bbox',
        'fallen_duration',
        'body_angle',
        'track_id',
        'status',
        'acknowledged_at',
        'acknowledged_by',
        'occurred_at',
    ];

    protected $casts = [
        'bbox' => 'array',
        'confidence' => 'decimal:3',
        'fallen_duration' => 'decimal:2',
        'body_angle' => 'decimal:2',
        'acknowledged_at' => 'datetime',
        'occurred_at' => 'datetime',
    ];

    /**
     * Get the camera that recorded this event.
     */
    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class);
    }

    /**
     * Get the user who acknowledged this event.
     */
    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    /**
     * Scope for active events.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for fall events.
     */
    public function scopeFalls($query)
    {
        return $query->where('type', 'fall');
    }

    /**
     * Scope for critical events.
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    /**
     * Scope for events by camera.
     */
    public function scopeForCamera($query, int $cameraId)
    {
        return $query->where('camera_id', $cameraId);
    }

    /**
     * Scope for recent events.
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('occurred_at', '>=', now()->subHours($hours));
    }

    /**
     * Get severity badge class.
     */
    public function getSeverityBadgeClassAttribute(): string
    {
        return match ($this->severity) {
            'critical' => 'bg-danger/20 text-danger border border-danger/30',
            'warning' => 'bg-warning/20 text-warning border border-warning/30',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-danger text-white animate-pulse',
            'acknowledged' => 'bg-yellow-500 text-white',
            'resolved' => 'bg-green-500 text-white',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'fall' => 'Fall Detected',
            'immobility' => 'Immobility',
            'other' => 'Other Emergency',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get snapshot URL.
     */
    public function getSnapshotUrlAttribute(): ?string
    {
        if (empty($this->snapshot_path)) {
            return null;
        }

        $path = str_replace('\\', '/', $this->snapshot_path);
        return asset('storage/' . ltrim($path, '/'));
    }

    /**
     * Check if event is new (less than 5 minutes old).
     */
    public function getIsNewAttribute(): bool
    {
        return $this->occurred_at->diffInMinutes(now()) < 5;
    }
}
