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
        $this->authorize('create', File::class);

        $request->validate([
            'file' => 'required|file|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $originalName = $request->file('file')->getClientOriginalName();
        $safeName = pathinfo($originalName, PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $safeName);
        $extension = $request->file('file')->getClientOriginalExtension();
        $fileName = $safeName . '_' . time() . '.' . $extension;

        $path = $request->file('file')->storeAs('public/uploads', $fileName);

        return response()->json(['path' => Storage::url($path)], 200);
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', File::class);

        $files = Storage::files('public/uploads');
        $fileUrls = array_map(function ($file) {
            return Storage::url($file);
        }, $files);

        return response()->json(['files' => $fileUrls]);
    }

    public function destroy($filename): JsonResponse
    {
        $this->authorize('delete', File::class);

        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $filename);

        if (Storage::exists('public/uploads/' . $safeName)) {
            Storage::delete('public/uploads/' . $safeName);
            return response()->json(['message' => 'File deleted successfully.']);
        }

        return response()->json(['message' => 'File not found.'], 404);
    }
}
