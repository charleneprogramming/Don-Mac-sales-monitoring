<?php

namespace App\Http\Controllers\Home\WEB;

use App\Http\Controllers\Controller;

class HomeWebController extends Controller
{
    public function index()
    {
        return view('Pages.Homepage.index');
    }
}
