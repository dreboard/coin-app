<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashController extends Controller
{

    public function index()
    {
        try{
            return view('users.dashboard');
        }catch (\Throwable $e){
            Log::error($e->getMessage());
            return view('welcome');
        }
    }

    public function viewProfile()
    {
        return view('users.settings.profile');
    }


}
