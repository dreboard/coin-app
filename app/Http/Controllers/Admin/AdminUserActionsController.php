<?php
/**
 * AdminUserActionsController is a class for viewing and managing users *
 * @package App\Http\Controllers\Admin
 * @author Andre Board
 * @version v1.0.0
 * @since v0.0.1
 * @see http://www.example.com/pear
 */
namespace App\Http\Controllers\Admin;

use App\Interfaces\Users\InterfaceUserRepository;
use App\Models\User;
use App\Models\Users\UserLogin;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserActionsController
{


    /**
     * @param InterfaceUserRepository $userRepository
     */
    public function __construct(private readonly InterfaceUserRepository $userRepository)
    {
    }

    /**
     * Find user from search form
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function findUser(Request $request): View|Factory|Application
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
     * @return Application|Factory|View
     */
    public function viewAllUsers(): View|Factory|Application
    {
        $users = $this->userRepository->getAllUsers();
        return view('admin.user-all', compact('users'));

    }


    /**
     * View a user
     *
     * @param int $user_id
     * @return Application|Factory|View
     */
    public function viewUser(int $user_id): View|Factory|Application
    {
        $user = User::where('id', $user_id)->withCount('logins')->first();
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
     * @return RedirectResponse
     */
    public function deleteUser(int $user_id): RedirectResponse
    {
        User::destroy($user_id);
        return redirect()->route('admin.view_users')->withInput()->withErrors(['User Deleted']);

    }

    /**
     * Clone a user
     *
     * @param int $user_id
     * @return RedirectResponse
     */
    public function cloneUser(int $user_id): RedirectResponse
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
     * @return RedirectResponse
     */
    public function changeUserStatus(int $user_id): RedirectResponse
    {
        $user = User::find($user_id);
        if ($user->account_status === 'Active') {
            $user->account_status = 0;
            $msg = 'User status is suspended';
            $with = 'withError';
        } elseif ($user->account_status === 'Suspended') {
            $user->account_status = 1;
            $msg = 'User status is active';
            $with = 'withSuccess';
        }

        $user->save();

        return redirect()->action(
            [AdminController::class, 'viewUser'], ['user_id' => $user_id]
        )->$with([$msg]);
    }

}
