<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\DetectionLog;
use App\Models\Employee;
use App\Models\EmployeePhoto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FaceRecognitionController extends Controller
{
    public function cameras(): JsonResponse
    {
        $cameras = Camera::select('id', 'name', 'rtsp_url', 'location', 'status')->get();
        return response()->json(['cameras' => $cameras]);
    }

    public function camera(int $id): JsonResponse
    {
        $camera = Camera::findOrFail($id);
        return response()->json(['camera' => $camera]);
    }

    public function faceEmbeddings(): JsonResponse
    {
        $employees = Employee::with(['photos' => function ($query) {
            $query->whereNotNull('embedding');
        }])->get();

        $data = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'name' => $employee->name,
                'department' => $employee->department,
                'embeddings' => $employee->photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'image_path' => $photo->image_path,
                        'embedding' => $photo->embedding,
                    ];
                })->values(),
            ];
        });

        return response()->json(['employees' => $data]);
    }

    public function storeDetectionLog(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'camera_id' => 'required|exists:cameras,id',
            'employee_id' => 'nullable|exists:employees,id',
            'employee_name' => 'nullable|string|max:255',
            'confidence' => 'required|numeric|between:0,1',
            'status' => 'required|in:recognized,unknown',
            'timestamp' => 'required|date',
            'snapshot_path' => 'nullable|string|max:500',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $log = DetectionLog::create([
            'camera_id' => $request->camera_id,
            'employee_id' => $request->employee_id,
            'employee_name' => $request->employee_name,
            'confidence' => $request->confidence,
            'status' => $request->status,
            'detected_at' => $request->timestamp,
            'snapshot_path' => $request->snapshot_path,
            'metadata' => $request->metadata,
        ]);

        // Update employee recognition count
        if ($request->employee_id && $request->status === 'recognized') {
            Employee::where('id', $request->employee_id)->increment('recognitions');
        }

        return response()->json(['log' => $log], 201);
    }

    public function updateEmbedding(Request $request, int $photoId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'embedding' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photo = EmployeePhoto::findOrFail($photoId);
        $photo->update(['embedding' => $request->embedding]);

        return response()->json(['photo' => $photo]);
    }
}