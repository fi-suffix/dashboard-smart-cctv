<?php

namespace Database\Seeders;

use App\Models\Camera;
use App\Models\DetectionLog;
use App\Models\Employee;
use App\Models\EmployeePhoto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create admin user
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // Create sample employees
        $employees = Employee::factory()->count(5)->create([
            'status' => 'active',
        ]);

        // Create sample cameras
        $cameras = Camera::factory()->count(4)->create([
            'status' => 'active',
        ]);

        // Create sample detection logs for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            // Recognized detections
            for ($j = 0; $j < rand(10, 30); $j++) {
                $employee = $employees->random();
                $camera = $cameras->random();
                
                DetectionLog::create([
                    'camera_id' => $camera->id,
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'confidence' => rand(70, 99) / 100,
                    'status' => 'recognized',
                    'detected_at' => $date->copy()->addHours(rand(6, 22))->addMinutes(rand(0, 59)),
                    'snapshot_path' => 'snapshots/' . $camera->id . '/' . $date->format('Y-m-d') . '/detection_' . rand(1000, 9999) . '.jpg',
                    'metadata' => [
                        'bbox' => [rand(100, 500), rand(100, 300), rand(80, 150), rand(80, 150)],
                    ],
                ]);
                
                $employee->increment('recognitions');
            }
            
            // Unknown detections
            for ($j = 0; $j < rand(2, 8); $j++) {
                $camera = $cameras->random();
                
                DetectionLog::create([
                    'camera_id' => $camera->id,
                    'employee_id' => null,
                    'employee_name' => null,
                    'confidence' => rand(30, 65) / 100,
                    'status' => 'unknown',
                    'detected_at' => $date->copy()->addHours(rand(6, 22))->addMinutes(rand(0, 59)),
                    'snapshot_path' => 'snapshots/' . $camera->id . '/' . $date->format('Y-m-d') . '/unknown_' . rand(1000, 9999) . '.jpg',
                    'metadata' => [
                        'bbox' => [rand(100, 500), rand(100, 300), rand(80, 150), rand(80, 150)],
                    ],
                ]);
            }
        }

        // Create employee photos (placeholder)
        foreach ($employees as $employee) {
            for ($i = 0; $i < 3; $i++) {
                EmployeePhoto::create([
                    'employee_id' => $employee->id,
                    'image_path' => 'employee_faces/' . $employee->employee_code . '_' . ($i + 1) . '.jpg',
                    'source' => 'upload',
                    'is_primary' => $i === 0,
                ]);
            }
        }
    }
}