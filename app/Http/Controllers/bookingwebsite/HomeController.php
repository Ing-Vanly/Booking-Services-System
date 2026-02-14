<?php

namespace App\Http\Controllers\bookingwebsite;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('bookingwebsite.home');
    }
}
