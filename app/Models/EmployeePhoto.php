<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'image_path',
        'embedding',
        'source',
        'is_primary',
    ];

    protected $casts = [
        'embedding' => 'array',
        'is_primary' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}