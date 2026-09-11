<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CameraController extends Controller
{
    public function index(): View
    {
        return view('dashboard.camera.index', [
            'cameras' => Camera::query()->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('dashboard.camera.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rtsp_url' => 'required|string|max:500',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive,maintenance',
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|max:100',
            'reconnect_interval' => 'nullable|integer|min:1|max:300',
        ]);

        $camera = Camera::create([
            'name' => $validated['name'],
            'rtsp_url' => $validated['rtsp_url'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'username' => $validated['username'] ?? null,
            'password' => $validated['password'] ?? null,
            'reconnect_interval' => $validated['reconnect_interval'] ?? 5,
        ]);

        return redirect()->route('dashboard.camera.index')->with('success', 'Camera added successfully.');
    }

    public function show(Camera $camera): View
    {
        return view('dashboard.camera.show', compact('camera'));
    }

    public function edit(Camera $camera): View
    {
        return view('dashboard.camera.edit', compact('camera'));
    }

    public function update(Request $request, Camera $camera): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rtsp_url' => 'required|string|max:500',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive,maintenance',
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|max:100',
            'reconnect_interval' => 'nullable|integer|min:1|max:300',
        ]);

        $camera->update($validated);

        return redirect()->route('dashboard.camera.index')->with('success', 'Camera updated successfully.');
    }

    public function destroy(Camera $camera): RedirectResponse
    {
        $camera->delete();
        return redirect()->route('dashboard.camera.index')->with('success', 'Camera deleted successfully.');
    }

    public function toggleStatus(Camera $camera): RedirectResponse
    {
        $camera->update([
            'status' => $camera->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Camera status updated.');
    }
}