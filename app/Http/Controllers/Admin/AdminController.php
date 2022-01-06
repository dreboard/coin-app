<?php

namespace App\Http\Controllers\Admin;

use App\Events\BannedUser;
use App\Events\UnbanUser;
use App\Http\Controllers\Controller;
use App\Interfaces\Users\InterfaceUserRepository;
use App\Models\User;
use App\Models\Users\UserLogin;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

/**
 *
 */
class AdminController extends Controller
{


    /**
     * @var InterfaceUserRepository
     */
    private $userRepository;

    /**
     * @param InterfaceUserRepository $userRepository
     */
    public function __construct(InterfaceUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.dashboard');
    }




}
