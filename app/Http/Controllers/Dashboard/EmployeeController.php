<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeePhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        return view('dashboard.employee.index', [
            'employees' => Employee::with('photos')->latest()->get(),
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

        // 3. Simpan File Foto ke Storage dan Generate Embedding
        $this->savePhotos($employee, $request->file('photos') ?? []);

        // 4. Reload embeddings di Python service
        $this->reloadEmbeddings();

        return redirect()->route('dashboard.employee.index')->with('success', 'Employee registered successfully.');
    }

    public function edit(Employee $employee): View
    {
        $employee->load('photos');

        return view('dashboard.employee.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => 'required|string|max:50|unique:employees,employee_code,'.$employee->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'department' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'delete_photo_ids' => 'nullable|array',
            'delete_photo_ids.*' => 'integer|exists:employee_photos,id',
        ]);

        $employee->update([
            'employee_code' => $validated['employee_code'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'department' => $validated['department'],
            'position' => $validated['position'] ?? null,
            'status' => $validated['status'],
        ]);

        // 3. Hapus foto yang dicentang untuk dihapus
        if (! empty($validated['delete_photo_ids'])) {
            foreach ($validated['delete_photo_ids'] as $photoId) {
                $photo = EmployeePhoto::where('employee_id', $employee->id)
                    ->where('id', $photoId)
                    ->first();

                if ($photo) {
                    $this->deletePhotoFile($photo);
                    $photo->delete();
                }
            }
        }

        // 4. Simpan foto baru + generate embedding
        $this->savePhotos($employee, $request->file('photos') ?? []);

        // 5. Reload embeddings di Python service
        $this->reloadEmbeddings();

        return redirect()->route('dashboard.employee.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        foreach ($employee->photos as $photo) {
            $this->deletePhotoFile($photo);
        }

        $employee->delete();

        $this->reloadEmbeddings();

        return redirect()->route('dashboard.employee.index')->with('success', 'Employee deleted successfully.');
    }

    /**
     * Simpan foto + minta embedding ke Python service.
     *
     * @param  UploadedFile[]  $photos
     */
    private function savePhotos(Employee $employee, array $photos): void
    {
        foreach ($photos as $photo) {
            $path = $photo->store('employee_faces', 'public');

            $embedding = $this->extractEmbedding($photo->getPathname());
            $isPrimary = $employee->photos()->count() === 0;

            $employee->photos()->create([
                'image_path' => $path,
                'embedding' => $embedding,
                'source' => 'upload',
                'is_primary' => $isPrimary,
            ]);
        }
    }

    private function extractEmbedding(string $filePath): ?array
    {
        try {
            $pythonServiceUrl = env('PYTHON_SERVICE_URL', 'http://localhost:8001');
            $apiKey = env('FACE_RECOGNITION_API_KEY', '');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
            ])->asMultipart()->post("{$pythonServiceUrl}/extract-embedding", [
                'file' => fopen($filePath, 'r'),
            ]);

            if ($response->successful() && $response->json('embedding')) {
                return $response->json('embedding');
            }

            logger()->warning('Python service returned no embedding: '.$response->status().' - '.$response->body());
        } catch (\Throwable $e) {
            // Continue without embedding if Python service unavailable
            logger()->warning('Failed to generate face embedding: '.$e->getMessage());
        }

        return null;
    }

    private function reloadEmbeddings(): void
    {
        try {
            $pythonServiceUrl = env('PYTHON_SERVICE_URL', 'http://localhost:8001');
            $apiKey = env('FACE_RECOGNITION_API_KEY', '');

            Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
            ])->post("{$pythonServiceUrl}/reload-embeddings");
        } catch (\Throwable $e) {
            logger()->warning('Failed to reload embeddings: '.$e->getMessage());
        }
    }

    private function deletePhotoFile(EmployeePhoto $photo): void
    {
        try {
            if ($photo->image_path && Storage::disk('public')->exists($photo->image_path)) {
                Storage::disk('public')->delete($photo->image_path);
            }
        } catch (\Throwable $e) {
            logger()->warning('Failed to delete photo file: '.$e->getMessage());
        }
    }
}
