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


    /**
     * Find user from search form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function findUser(Request $request)
    {
        $request->validate([
            'searchTerm' => 'required'
        ]);
        $searchTerm = $request->input('searchTerm');
        $data = User::where('id', '=', $searchTerm)
            ->orWhere('name', 'LIKE', "%{$searchTerm}%")->get();
        return view('admin.user-results', ['users' => $data]);
    }


    /**
     * View all users
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewAllUsers()
    {
        $users = $this->userRepository->getAllUsers();
        return view('admin.user-all', compact('users'));

    }


    /**
     * View all users
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewAllBannedUsers()
    {
        $users = $this->userRepository->getAllBannedUsers();
        //dd($users);
        return view('admin.user-all-banned', compact('users'));

    }



    /**
     * View a user
     *
     * @param int $user_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewUser(int $user_id)
    {
        $user = User::where('id',$user_id)->withCount('logins')->first();


        $last_login = UserLogin::where('user_id', $user_id)
            ->select('created_at')
            ->orderBy('created_at', 'DESC')
            ->first();
        return view('admin.user-view', ['user' => $user, 'last_login' => $last_login]);

    }

    /**
     * Delete a user
     *
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(int $user_id)
    {
        User::destroy($user_id);
        return redirect()->route('admin.view_users')->withInput()->withErrors(['User Deleted']);

    }

    /**
     * Clone a user
     *
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
     * Change a user status
     *
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

    /**
     * @param int $user_id
     */
    public function banUserPermanent(int $user_id)
    {
        $user = User::find($user_id);
        $user->ban([
            'expired_at' => '+1 month',
        ]);

    }

    /**
     * Ban a user
     *
     * @source {https://github.com/cybercog/laravel-ban#prepare-bannable-model}
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function banUser(Request $request): \Illuminate\Http\RedirectResponse
    {
        try{
            $request->validate([
                'length' => 'required'
            ]);

            $user = User::find($request->input('user_id'));
            event(new BannedUser($user, (int)$request->input('length')));

            return redirect()->action(
                [AdminController::class, 'viewUser'],
                ['user_id' => $request->input('user_id')]
            )->with('status', 'User is banned');

        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['msg' => 'User could not be banned']);
        }
    }


    /**
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unbanAllUsers(int $user_id)
    {
        app(\Cog\Contracts\Ban\BanService::class)->deleteExpiredBans();
        return redirect()->action(
            [AdminController::class, 'viewAllUsers']
        );
    }

    /**
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unbanUser(int $user_id)
    {
        try {
            $user = User::find($user_id);
            event(new UnbanUser($user));
            return redirect()->action(
                [AdminController::class, 'viewUser'],
                ['user_id' => $user_id]
            )->with('success', 'User is activated');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'User could not be banned']);
        }
    }

}
