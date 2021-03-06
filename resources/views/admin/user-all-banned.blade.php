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
        <table id="user_datatable" class="table datatable">
            <thead>
            <tr>
                <th class="text-start">ID #</th>
                <th>Name</th>
                <th>Banned</th>
                <th class="text-center">View</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th class="text-start">ID #</th>
                <th>Name</th>
                <th>Banned</th>
                <th class="text-center">View</th>
            </tr>
            </tfoot>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td class="text-start">{{ $user->name }}</td>
                    <td class="text-start">
                         {{ $user->banned_at->format('l F jS Y, g:ia') }}
                    </td>
                    <td class="text-center"><a href="{{ route('admin.view_user', ['user_id' => $user->id]) }}">View</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>



    </div>
@endsection
