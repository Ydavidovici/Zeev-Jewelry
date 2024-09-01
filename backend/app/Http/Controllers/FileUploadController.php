<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class FileUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-file', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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
        if (!Gate::allows('view-any-file', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $files = Storage::files('public/uploads');
        $fileUrls = array_map(function ($file) {
            return Storage::url($file);
        }, $files);

        return response()->json(['files' => $fileUrls]);
    }

    public function destroy($filename): JsonResponse
    {
        if (!Gate::allows('delete-file', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $filename);

        if (Storage::exists('public/uploads/' . $safeName)) {
            Storage::delete('public/uploads/' . $safeName);
            return response()->json(['message' => 'File deleted successfully.']);
        }

        return response()->json(['message' => 'File not found.'], 404);
    }
}
