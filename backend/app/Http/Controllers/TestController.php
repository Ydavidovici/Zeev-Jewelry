<?php

// app/Http/Controllers/TestController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestController extends Controller
{
    public function adminAccess()
    {
        if (Gate::allows('is-admin')) {
            return response()->json(['message' => 'You are an admin!'], 200);
        }

        return response()->json(['message' => 'Access denied'], 403);
    }
}
