<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $tools = \App\Models\Tool::latest()->take(8)->get();
        return view('welcome', compact('tools'));
    }
}