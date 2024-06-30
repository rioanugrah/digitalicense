<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="author" content="Digital License">
    <meta name="description" content="@yield('description')">
    <meta name="theme-color" content="#00215E">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/icon.png') }}">
    <meta name="keywords" content="@yield('keywords')">
    <link rel="canonical" href="@yield('canonical')">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta property="og:locale:alternate" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta property="og:type" content="Digital License">
    <meta property="og:title" content="@yield('title')">
    {{-- <meta property="og:url" content="{{ url('/') }}"> --}}
    <meta property="og:site_name" content="Digital License">
    <meta property="og:description" content="@yield('description')">
    <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:standard">
    <title>@yield('title')</title>
    <script src="{{ URL::asset('build/js/layout.js') }}"></script>
    <link href="{{ URL::asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    @yield('css')
</head>

<body data-layout="horizontal" data-layout-size="boxed">
    <div id="layout-wrapper">
        @include('layouts.frontend.header')
        @include('layouts.frontend.topnav')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('layouts.frontend.footer')
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ URL::asset('build/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
    <!-- Icon -->
    <script src="{{ URL::asset('build/js/pages/icon.js') }}"></script>
</body>

</html>
