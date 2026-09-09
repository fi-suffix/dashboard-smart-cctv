<?php

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('employee add form is available', function () {
    $this->get(route('dashboard.employee.create'))
        ->assertOk()
        ->assertSee('Employee Information');
});

test('employee can be created and appears in the index', function () {
    $response = $this->post(route('dashboard.employee.store'), [
        'employee_code' => 'EMP-001',
        'name' => 'Budi Santoso',
        'department' => 'Security',
        'status' => 'active',
    ]);

    $response->assertRedirect(route('dashboard.employee.index'));
    $this->assertDatabaseHas('employees', [
        'employee_code' => 'EMP-001',
        'name' => 'Budi Santoso',
    ]);

    $this->get(route('dashboard.employee.index'))
        ->assertOk()
        ->assertSee('Budi Santoso');
});

test('employee creation validates required fields', function () {
    $this->from(route('dashboard.employee.create'))
        ->post(route('dashboard.employee.store'), [])
        ->assertRedirect(route('dashboard.employee.create'))
        ->assertSessionHasErrors(['employee_code', 'name', 'department', 'status']);

    expect(Employee::count())->toBe(0);
});
