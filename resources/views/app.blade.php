<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kanban') }} | @yield('title', "")</title>

    <meta name="keywords" content="{{ trans('meta.keyword') }}"/>
    <meta name="description" content="{{ trans('meta.og_description') }}"/>
    {{--    <link rel="shortcut icon" href="{{ asset('img/favicon.png?v2', config('site.ssl_tf')) }}"/>--}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com"/>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>
    @section('styles')
    @show

    <script>
        window._KanbanConfig = {
            BASE: '{{ request()->root() }}',
            Token: '{!! csrf_token() !!}'
        };
    </script>
</head>
<body class="main-body">
<div id="app">
    @yield('content')
</div>
<script src="{{ mix('js/app.js') }}" defer></script>
@section('scripts')
@show
</body>
</html>
