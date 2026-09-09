<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['employee_code', 'name', 'email', 'department', 'position', 'status', 'recognitions'])]
class Employee extends Model
{
    protected function casts(): array
    {
        return [
            'recognitions' => 'integer',
        ];
    }
}
