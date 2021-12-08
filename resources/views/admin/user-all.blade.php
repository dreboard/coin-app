@extends('layouts.admin.main')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    @push('header')
        <h3 class="mt-4">Admin Dashboard</h3>
    @endpush

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a> </li>
        <li class="breadcrumb-item active"><a href="{{ route('admin.view_users') }}">Users</a></li>
    </ol>

    <div class="card-body">

        <div class="mb-4">
            <a class="btn btn-danger" href="{{ route('admin.view_banned_users') }}">Suspended Users</a>
        </div>


        <table id="user_datatable" class="table datatable">
            <thead>
            <tr>
                <th>ID #</th>
                <th>Name</th>
                <th>Status</th>
                <th>View</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>ID #</th>
                <th>Name</th>
                <th>Status</th>
                <th>View</th>
            </tr>
            </tfoot>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="text-start">{{ $user->id }}</td>
                    <td class="text-start">{{ $user->name }}</td>
                    <td class="text-start">
                        <span class="">{{ $user->banned_at }}</span>
                        @if ($user->banned_at == null)
                            <span class="text-success">Active</span>
                        @else
                            <span class="text-danger"> Banned {{ $user->banned_at->format('l F jS Y, g:ia') }}</span>
                        @endif
                    </td>
                    <td class="text-end"><a href="{{ route('admin.view_user', ['user_id' => $user->id]) }}">View</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>



    </div>
@endsection
