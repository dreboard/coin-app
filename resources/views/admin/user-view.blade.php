@extends('layouts.admin.main')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h3 class="mt-4">Admin Dashboard</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a> </li>
        <li class="breadcrumb-item active"><a href="{{ route('admin.view_users') }}">Users</a></li>
    </ol>

    <div class="container">
        <div class="main-body">

            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
{{--                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">--}}
                                <div class="mt-3">
                                    <h4>{{ $user->name }}</h4>
                                    <p class="text-secondary mb-1">Logins: {{ $user->logins_count }}</p>
                                    <button class="btn btn-outline-primary">Message</button>

                                        <a class="btn btn-warning" href="{{ route('admin.clone_user', $user->id) }}" role="button">Clone</a>
                                    @canBeImpersonated($user)
                                    @endCanBeImpersonated

                                    <button id="delete_user" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel">Delete {{ $user->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    You are about to delete {{ $user->name }}, and all content.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a class="btn btn-danger" href="{{ route('admin.delete_user', ['user_id' => $user->id])  }}" role="button">YES</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0">Username</h6>
                                <span class="text-secondary">{{ $user->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0">Email</h6>
                                <span class="text-secondary">{{ $user->email }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Status</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @if(Cache::has('user-is-online-' . $user->id))
                                        <span class="text-success">Online</span>
                                    @else
                                        <span class="text-secondary">Offline</span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Last Login</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                @empty($last_login->created_at)
                                        <span class="text-danger">No Login Data</span>
                                    @else
                                        {{$last_login->created_at->format('l jS F Y, g:ia')}}
                                    @endempty
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Verified</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @empty($user->email_verified_at)
                                        <span class="text-danger">User Unverified</span>
                                    @else
                                        {{ $user->email_verified_at->format('l jS F Y, g:ia') ?? 'No Data' }}
                                    @endempty

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">
                                        @if($user->isBanned())
                                            <span class="text-danger">Suspended</span>
                                        @else
                                            <span class="text-success">Active</span>
                                        @endif
                                    </h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @if($user->isBanned())
                                        @if ($user->banned_at == null)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger"> {{ $user->banned_at->format('F jS Y, g:ia') }}</span>
                                        @endif
                                            <a id="status_user" class="btn btn-success" href="{{ route('admin.unban_user', ['user_id' => $user->id])  }}">Activate </a>
                                    @else
                                        <form class="row row-cols-lg-auto g-3 align-items-center" method="POST" action="{{ route('admin.ban_user')  }}">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$user->id}}">
                                            <div class="col-8">
                                                <label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
                                                <select class="form-select" name="length">
                                                    <option selected value=""> Suspend User</option>
                                                    <option value="7">1 Week</option>
                                                    <option value="30">1 Month</option>
                                                    <option value="0">Forever</option>
                                                </select>
                                            </div>

                                            <div class="col-4">
                                                <button type="submit" class="btn btn-danger">Suspend</button>
                                            </div>
                                        </form>
                                    @endif






                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info " target="__blank" href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gutters-sm">
                        <div class="col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>
                                    <small>Web Design</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Website Markup</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>
                                    <small>Web Design</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Website Markup</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>

        </div>
    </div>
@endsection
