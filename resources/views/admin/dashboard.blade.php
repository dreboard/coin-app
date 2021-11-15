@extends('layouts.admin.main')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')

    <h3 class="mt-4">Dashboard</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('admin.view_users') }}">Users</a></li>
    </ol>
    <p>This is my admin content.</p>
@endsection
