<?php

namespace Database\Factories;

use App\Models\Camera;
use Illuminate\Database\Eloquent\Factories\Factory;

class CameraFactory extends Factory
{
    protected $model = Camera::class;

    public function definition(): array
    {
        $locations = ['Main Entrance', 'Parking Lot A', 'Parking Lot B', 'Server Room', 'Reception', 'Warehouse', 'Loading Dock', 'Back Exit'];
        $location = $this->faker->randomElement($locations);
        
        return [
            'name' => 'Cam-' . strtoupper($this->faker->bothify('??-###')),
            'rtsp_url' => 'rtsp://admin:password@192.168.1.' . rand(100, 200) . ':554/stream' . rand(1, 4),
            'location' => $location,
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'inactive', 'maintenance']),
            'username' => 'admin',
            'password' => 'password123',
            'reconnect_interval' => 5,
            'last_connected_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 hour', 'now'),
        ];
    }
}