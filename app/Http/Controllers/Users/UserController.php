<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class UserController extends Controller
{


    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function viewProfile()
    {
        return view('users.settings.profile');
    }

    public function changePassword()
    {
        return view('users.settings.edit');
    }

    /**
     * User edit current password
     *
     * @param Request $request
     */
    public function savePassword(Request $request)
    {
        $user = Auth::user();
        $userPassword = $user->password;
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|same:confirm_password|min:6',
            'confirm_password' => 'required',
        ]);

        if (!Hash::check($request->current_password, $userPassword)) {
            return back()->withErrors(['current_password'=>'Current password is not valid']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('status','password successfully updated');
    }


    /**
     * Toggle user visibility
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleVisibility(Request $request): JsonResponse
    {
        if($request->input('profile_visibility') == 0){
            $int = 1;
        } else {
            $int = 0;
        }

        $user = User::find(Auth::user()->id);
        $user->profile_visibility = $int;
        $user->save();
        $user->refresh();
        return response()->json((int)$int);
    }
}
