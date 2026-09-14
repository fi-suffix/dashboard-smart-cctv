<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetectionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'camera_id',
        'employee_id',
        'employee_name',
        'confidence',
        'status',
        'detected_at',
        'snapshot_path',
        'metadata',
    ];

    protected $casts = [
        'confidence' => 'decimal:4',
        'detected_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeRecognized($query)
    {
        return $query->where('status', 'recognized');
    }

    public function scopeUnknown($query)
    {
        return $query->where('status', 'unknown');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('detected_at', today());
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('detected_at', [$startDate, $endDate]);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'recognized' 
            ? 'bg-success/10 text-success' 
            : 'bg-danger/10 text-danger';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'recognized' ? 'Recognized' : 'Unknown';
    }

    public function getConfidencePercentageAttribute(): string
    {
        return number_format($this->confidence * 100, 1) . '%';
    }

    public function getSnapshotUrlAttribute(): ?string
    {
        if (empty($this->snapshot_path)) {
            return null;
        }

        // Python saves with Windows backslashes; normalize for HTTP.
        $path = str_replace('\\', '/', $this->snapshot_path);
        return asset(ltrim($path, '/'));
    }
}