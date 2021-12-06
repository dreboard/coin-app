<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * DashController
 */
class DashController extends Controller
{

    /**
     * Main dashboard view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        try{
            return view('users.dashboard');
        }catch (Throwable $e){
            Log::error($e->getMessage());
            return view('welcome');
        }
    }



}
