<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashController extends Controller
{

    public function index()
    {
        return view('users.dashboard');
    }

    public function viewProfile()
    {
        return view('users.settings.profile');
    }

}
