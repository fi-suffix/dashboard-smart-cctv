<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        return view('dashboard.employee.index', [
            'employees' => Employee::query()->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('dashboard.employee.add');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input Data & Foto
        $validated = $request->validate([
            'employee_code' => 'required|string|max:50|unique:employees,employee_code',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'department' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Simpan Data Employee ke Database
        $employee = Employee::create([
            'employee_code' => $validated['employee_code'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'department' => $validated['department'],
            'position' => $validated['position'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        // 3. Simpan File Foto ke Storage dan Database Relasi
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('employee_faces', 'public');

                $employee->photos()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('dashboard.employee.index')->with('success', 'Employee registered successfully.');
    }
}
