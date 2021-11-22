<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Users\InterfaceUserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function findUser(Request $request)
    {
        $request->validate([
            'searchTerm' => 'required'
        ]);

        $searchTerm = $request->input('searchTerm');
        //$data = User::where('id', '=', $searchTerm)->first();
        //dd($data); die;

        $data = User::where('id', '=', $searchTerm)
            ->orWhere('name', 'LIKE', "%{$searchTerm}%")->get();
        //dd($data); die;
        return view('admin.user-results', ['users' => $data]);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewAllUsers()
    {
        $users = User::all('id', 'name', 'created_at', 'account_status');
        return view('admin.user-all', compact('users'));

    }

    /**
     * @param int $user_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewUser(int $user_id)
    {
        $user = User::where('id',$user_id)->withCount('logins')->first();
        $user->last_login = DB::table('login_history')->where('user_id', '=', $user_id)->orderBy('created_at', 'DESC')->first() ?? 'No Data';
        return view('admin.user-view', compact('user'));

    }

    /**
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(int $user_id)
    {
        User::destroy($user_id);
        return redirect()->route('admin.view_users')->withInput()->withErrors(['User Deleted']);

    }

    /**
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cloneUser(int $user_id)
    {
        $user = User::find($user_id);
        Auth::user()->impersonate($user);
        return redirect()->route('admin.dashboard')->withInput()->withErrors(['User cloned']);
    }


    /**
     * @param int $user_id
     * @param int $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeUserStatus(int $user_id): \Illuminate\Http\RedirectResponse
    {
        $user = User::find($user_id);
        if ($user->account_status === 'Active'){
            $user->account_status = 0;
            $msg = 'User status is suspended';
            $with = 'withError';
        }elseif ($user->account_status === 'Suspended') {
            $user->account_status = 1;
            $msg = 'User status is active';
            $with = 'withSuccess';
        }

        $user->save();

        return redirect()->action(
            [AdminController::class, 'viewUser'], ['user_id' => $user_id]
        );
    }

}
