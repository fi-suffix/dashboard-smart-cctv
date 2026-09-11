<?php

namespace Database\Factories;

use App\Models\DetectionLog;
use App\Models\Camera;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetectionLogFactory extends Factory
{
    protected $model = DetectionLog::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['recognized', 'recognized', 'recognized', 'unknown']);
        
        return [
            'camera_id' => Camera::inRandomOrder()->first()->id ?? 1,
            'employee_id' => $status === 'recognized' ? Employee::inRandomOrder()->first()->id : null,
            'employee_name' => $status === 'recognized' ? Employee::inRandomOrder()->first()->name : null,
            'confidence' => $status === 'recognized' ? $this->faker->randomFloat(4, 0.7, 0.99) : $this->faker->randomFloat(4, 0.3, 0.65),
            'status' => $status,
            'detected_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'snapshot_path' => 'snapshots/' . rand(1, 4) . '/' . now()->format('Y-m-d') . '/detection_' . rand(1000, 9999) . '.jpg',
            'metadata' => [
                'bbox' => [rand(100, 500), rand(100, 300), rand(80, 150), rand(80, 150)],
            ],
        ];
    }
}