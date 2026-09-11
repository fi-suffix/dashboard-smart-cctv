<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'name',
        'email',
        'department',
        'position',
        'status',
        'recognitions',
    ];

    protected $casts = [
        'recognitions' => 'integer',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(EmployeePhoto::class);
    }

    public function detectionLogs(): HasMany
    {
        return $this->hasMany(DetectionLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getPrimaryPhotoAttribute()
    {
        return $this->photos()->where('is_primary', true)->first() 
            ?? $this->photos()->first();
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'active' 
            ? 'bg-success/10 text-success' 
            : 'bg-text-secondary/10 text-text-secondary';
    }
}