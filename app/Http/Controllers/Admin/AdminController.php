<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }


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


    public function viewAllUsers()
    {
        $users = User::all('id', 'name', 'created_at');
        //$user = User::where('id', '=', $user_id)->first();
        //dd($user->name);
        return view('admin.user-all', compact('users'));

    }

    public function viewUser(int $user_id)
    {
        $user = User::find($user_id);
        //$user = User::where('id', '=', $user_id)->first();
        //dd($user->name);
        return view('admin.user-view', compact('user'));

    }

    public function deleteUser(int $user_id)
    {
        User::destroy($user_id);
        return redirect()->route('admin.view_users')->withInput()->withErrors(['User Deleted']);

    }

    public function cloneUser(int $user_id)
    {
        $user = User::find($user_id);
        Auth::user()->impersonate($user);
        return view('admin.dashboard');
    }
}
