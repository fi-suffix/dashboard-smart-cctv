<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $defaults = Setting::getGroup('camera_defaults');
        return view('dashboard.camera.create', compact('defaults'));
    }

    public function store(Request $request): RedirectResponse
    {
        $defaults = Setting::getGroup('camera_defaults');
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
            'status' => $validated['status'] ?? $defaults['default_status'] ?? 'active',
            'username' => $validated['username'] ?? null,
            'password' => $validated['password'] ?? null,
            'reconnect_interval' => $validated['reconnect_interval'] ?? $defaults['reconnect_interval'] ?? 5,
        ]);

        if ($camera->status === 'active') {
            $this->notifyPython('start', $camera->id);
        }

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

        // Keep current password if blank
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $camera->update($validated);

        // Restart stream so new config is applied
        $this->notifyPython('stop', $camera->id);
        if ($camera->status === 'active') {
            $this->notifyPython('start', $camera->id);
        }

        return redirect()->route('dashboard.camera.index')->with('success', 'Camera updated successfully.');
    }

    public function destroy(Camera $camera): RedirectResponse
    {
        $this->notifyPython('stop', $camera->id);

        $camera->delete();

        return redirect()->route('dashboard.camera.index')->with('success', 'Camera deleted successfully.');
    }

    public function toggleStatus(Camera $camera): RedirectResponse
    {
        $camera->update([
            'status' => $camera->status === 'active' ? 'inactive' : 'active',
        ]);

        if ($camera->status === 'active') {
            $this->notifyPython('start', $camera->id);
        } else {
            $this->notifyPython('stop', $camera->id);
        }

        return back()->with('success', 'Camera status updated.');
    }

    private function notifyPython(string $action, int $cameraId): void
    {
        try {
            $base = rtrim(Setting::getValue('api_integration.python_service_url', env('PYTHON_SERVICE_URL', 'http://localhost:8001')), '/');
            Http::timeout(3)->post("{$base}/cameras/{$cameraId}/{$action}");
        } catch (\Throwable $e) {
            logger()->warning('Failed to notify Python service: '.$e->getMessage());
        }
    }
}
