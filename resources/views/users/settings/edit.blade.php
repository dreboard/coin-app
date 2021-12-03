@extends('layouts.user.main')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p>Change password</p>

     {{ __(auth()->user()->password) }}
    <div class="container">
        <div class="main-body">

            <form method="post" action="{{ route('user.user_save_password') }}">
                @csrf
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-auto">
                        <label for="current_password" class="col-form-label">Password</label>
                    </div>
                    <div class="col-auto">
                        <input name="current_password" type="password" id="current_password" class="form-control" aria-describedby="passwordHelpInline">
                    </div>
                    <div class="col-auto">
                        <span id="passwordHelpInline" class="form-text">
                          Your current password
                        </span>
                    </div>
                </div>
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-auto">
                        <label for="password" class="col-form-label">Password</label>
                    </div>
                    <div class="col-auto">
                        <input name="password" type="password" id="password" class="form-control" aria-describedby="passwordHelpInline">
                    </div>
                    <div class="col-auto">
                        <span id="passwordHelpInline" class="form-text">
                          New Password, 8-20 characters long.
                        </span>
                    </div>
                </div>
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-auto">
                        <label for="confirm_password" class="col-form-label">Password</label>
                    </div>
                    <div class="col-auto">
                        <input name="confirm_password" type="password" id="confirm_password" class="form-control" aria-describedby="passwordHelpInline">
                    </div>
                    <div class="col-auto">
                        <span id="passwordHelpInline" class="form-text">
                          Confirm new password
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>

        </div>
    </div>


@endsection
