@extends('layouts.default')

@section('content')
    <div class="offset-md-2 col-md-8">
        <div class="card">
            <div class="card-header">登陆</div>
            <div class="card-body">

                @include('shared._errors')

                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">邮箱</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email"
                               aria-describedby="email"
                               placeholder="请输入邮箱">
                    </div>
                    <div class="form-group">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}"
                               id="password" placeholder="请输入密码">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">记住我</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">登陆</button>
                </form>
                <hr>
                <p>还没有账号?<a href="{{ route('user.create') }}"> 现在注册</a>!</p>
            </div>
        </div>
    </div>
@endsection

@section('login', 'active')