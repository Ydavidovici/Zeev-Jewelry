<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function siteSettings()
    {
        // Add logic for site settings management
        return view('admin.settings');
    }

    public function analytics()
    {
        // Add logic for analytics and reports
        return view('admin.analytics');
    }
}
