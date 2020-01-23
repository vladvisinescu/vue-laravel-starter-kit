<?php

namespace App\Http\Controllers\SinglePage;

use App\Http\Controllers\Controller;

class SinglePageController extends Controller
{

    public function index()
    {
        // Easy like a Sunday morning
        return view('app');
    }
}
