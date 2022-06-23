<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Coin App') }}</title>
    @include('layouts.styles')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script async>
        let ENVIRONMENT = "{{ config('app.env') }}";
        let SITE_URL = "{{ config('app.url') }}";
        let USER_ID = "{{ auth()->user()->id }}";
        let CSRF_TOKEN = "{{ csrf_token() }}";

    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    @include('layouts.ga')

</head>
<body class="sb-nav-fixed">

@include('layouts.user.top-nav')
<div id="layoutSidenav">
    @include('layouts.user.side-nav')
    <div id="layoutSidenav_content">
        <main>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            <div id="app" class="container-fluid px-4">
                @stack('header')
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                @yield('content')


            </div>
        </main>
        @include('layouts.user.footer')
    </div>
</div>
@include('layouts.scripts')
</body>
</html>
