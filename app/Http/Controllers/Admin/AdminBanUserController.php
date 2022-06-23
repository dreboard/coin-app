<?php

namespace App\Http\Controllers\Admin;

use App\Events\BannedUser;
use App\Events\UnbanUser;
use App\Interfaces\Users\InterfaceUserRepository;
use App\Models\User;
use Cog\Contracts\Ban\BanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class AdminBanUserController
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
     * View all users
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewAllBannedUsers()
    {
        $users = $this->userRepository->getAllBannedUsers();
        return view('admin.user-all-banned', compact('users'));

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
        $request->validate([
            'length' => 'required'
        ]);

        try{
            $user = User::find($request->input('user_id'));
            event(new BannedUser($user, (int)$request->input('length')));

            return redirect()->action(
                [AdminUserActionsController::class, 'viewUser'],
                ['user_id' => $request->input('user_id')]
            )->with('error', 'User is banned');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'User could not be banned']);
        }
    }


    /**
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unbanAllUsers(int $user_id)
    {
        app(BanService::class)->deleteExpiredBans();
        return redirect()->action(
            [AdminUserActionsController::class, 'viewAllUsers']
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
                [AdminUserActionsController::class, 'viewUser'],
                ['user_id' => $user_id]
            )->with('success', 'User is activated');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'User could not be banned']);
        }
    }
}
