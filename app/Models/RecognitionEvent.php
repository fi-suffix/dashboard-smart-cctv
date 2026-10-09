<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecognitionEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_uuid',
        'camera_id',
        'employee_id',
        'type',
        'similarity',
        'track_id',
        'snapshot_path',
        'bbox',
        'occurred_at',
    ];

    protected $casts = [
        'type' => 'string',
        'similarity' => 'float',
        'bbox' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForCamera($query, int $cameraId)
    {
        return $query->where('camera_id', $cameraId);
    }

    public function getIsUnknownAttribute(): bool
    {
        return $this->type === 'unknown';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->type === 'known'
            ? 'bg-success/10 text-success'
            : 'bg-danger/10 text-danger';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->type === 'known' ? 'Known' : 'Unknown';
    }

    public function getSimilarityPercentageAttribute(): string
    {
        return $this->similarity !== null
            ? number_format($this->similarity * 100, 1).'%'
            : '—';
    }
}
