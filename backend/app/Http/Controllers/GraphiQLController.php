<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class GraphiQLController extends BaseController
{
    public function show()
    {
        return view('graphiql');
    }
}
