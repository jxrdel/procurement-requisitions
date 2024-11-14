<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckStaffController extends Controller
{
    public function index()
    {
        return view('check-staff');
    }
}
