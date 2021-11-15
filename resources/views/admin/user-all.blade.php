@extends('layouts.admin.admin')

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

    <div class="card-body">
        <table id="user_datatable" class="table datatable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>View</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>View</th>
            </tr>
            </tfoot>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="text-start">ID #{{ $user->id }}</td>
                    <td class="text-start">{{ $user->name }}</td>
                    <td class="text-end"><a href="{{ route('admin.view_user', ['user_id' => $user->id]) }}">View</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>



    </div>
@endsection
