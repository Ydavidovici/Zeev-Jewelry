<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        // Uncomment the following line if you have a policy in place
        // $this->authorize('upload', File::class);

        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Sanitize file name to prevent security issues
        $originalName = $request->file('file')->getClientOriginalName();
        $safeName = pathinfo($originalName, PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $safeName);
        $extension = $request->file('file')->getClientOriginalExtension();
        $fileName = $safeName . '_' . time() . '.' . $extension;

        // Store the file securely
        $path = $request->file('file')->storeAs('public/uploads', $fileName);

        // Return the file URL
        return response()->json(['path' => Storage::url($path)], 200);
    }

    public function index()
    {
        $files = Storage::files('public/uploads');
        $fileUrls = array_map(function ($file) {
            return Storage::url($file);
        }, $files);

        return view('uploads.index', compact('fileUrls'));
    }

    public function destroy(Request $request, $filename)
    {
        // Uncomment the following line if you have a policy in place
        // $this->authorize('delete', File::class);

        // Validate the file name
        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $filename);

        // Delete the file
        if (Storage::exists('public/uploads/' . $safeName)) {
            Storage::delete('public/uploads/' . $safeName);
            return redirect()->back()->with('success', 'File deleted successfully.');
        }

        return redirect()->back()->with('error', 'File not found.');
    }
}
