<?php

namespace Database\Factories;

use App\Models\EmployeePhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeePhotoFactory extends Factory
{
    protected $model = EmployeePhoto::class;

    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::inRandomOrder()->first()->id ?? 1,
            'image_path' => 'employee_faces/employee_' . rand(1000, 9999) . '.jpg',
            'embedding' => null,
            'source' => 'upload',
            'is_primary' => false,
        ];
    }
}