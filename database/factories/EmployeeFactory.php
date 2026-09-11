<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $departments = ['Security', 'Engineering', 'HR', 'Finance', 'Operations', 'IT', 'Administration'];
        $positions = ['Security Guard', 'System Administrator', 'HR Manager', 'Financial Analyst', 'Operations Manager', 'Software Engineer', 'Office Administrator'];
        
        return [
            'employee_code' => 'EMP-' . strtoupper($this->faker->unique()->bothify('####')),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'department' => $this->faker->randomElement($departments),
            'position' => $this->faker->randomElement($positions),
            'status' => 'active',
            'recognitions' => 0,
        ];
    }
}