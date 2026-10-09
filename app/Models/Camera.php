<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Camera extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rtsp_url',
        'rtsp_url_main',
        'rtsp_url_sub',
        'location',
        'status',
        'recognition_enabled',
        'username',
        'password',
        'reconnect_interval',
        'last_connected_at',
    ];

    protected $casts = [
        'recognition_enabled' => 'boolean',
        'reconnect_interval' => 'integer',
        'last_connected_at' => 'datetime',
    ];

    public function detectionLogs(): HasMany
    {
        return $this->hasMany(DetectionLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getEffectiveMainUrlAttribute(): string
    {
        return $this->rtsp_url_main ?: $this->rtsp_url;
    }

    public function getEffectiveSubUrlAttribute(): string
    {
        return $this->rtsp_url_sub ?: $this->rtsp_url;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-success/10 text-success',
            'inactive' => 'bg-text-secondary/10 text-text-secondary',
            'maintenance' => 'bg-warning/10 text-warning',
            default => 'bg-text-secondary/10 text-text-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Online',
            'inactive' => 'Offline',
            'maintenance' => 'Maintenance',
            default => ucfirst($this->status),
        };
    }
}
