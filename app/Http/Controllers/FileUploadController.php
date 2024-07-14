<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $path = $request->file('file')->store('public/uploads');

        return response()->json(['path' => Storage::url($path)], 200);
    }
}
