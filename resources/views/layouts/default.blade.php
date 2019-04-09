<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('site.title'))</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"></script>
    <script src="{{ app('url')->asset('js/index.js') }}"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="/">{{ setting('site.title') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link @yield('index')" href="/">上传</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('search')" href="/search/1">搜索</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('about')" href="/about">关于我们</a>
            </li>
        </ul>
        <div>
            @if(Auth::check())
            <div class="btn-group">
                <button type="button" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item"
                       href="{{ route('user.show', Auth::user()) }}">个人中心</a>
                    <a class="dropdown-item"
                       href="{{ route('my_cloud') }}">我的云上</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="logout">
                        <form action="{{ route('logout') }}" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button class="btn btn-block btn-danger btn-sm" type="submit" name="button">登出</button>
                        </form>
                    </a>
                </div>
            </div>
            @else
            <a href="{{ route('user.create') }}" class="btn btn-outline-light ml-sm-2 @yield('register')">注册</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light ml-sm-2 @yield('login')">登陆</a>
            @endif
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 1.5rem">
    @include('shared.messages')
    @yield('content')
    @include('layouts.footer')
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>