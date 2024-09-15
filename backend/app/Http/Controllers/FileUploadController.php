<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class FileUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request): JsonResponse
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = $file->hashName();

        // Store the file in the 'public/uploads' directory
        $path = $file->storeAs('uploads', $fileName, 'public');

        return response()->json(['path' => Storage::url($path)], 200);
    }

    public function index(): JsonResponse
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $files = Storage::disk('public')->files('uploads');
        $fileUrls = array_map(function ($file) {
            return Storage::url($file);
        }, $files);

        return response()->json(['files' => $fileUrls]);
    }

    public function destroy($filename): JsonResponse
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $filename);

        if (Storage::disk('public')->exists('uploads/' . $safeName)) {
            Storage::disk('public')->delete('uploads/' . $safeName);
            return response()->json(['message' => 'File deleted successfully.']);
        }

        return response()->json(['message' => 'File not found.'], 404);
    }
}
