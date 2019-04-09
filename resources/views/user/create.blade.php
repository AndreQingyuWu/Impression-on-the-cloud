@extends('layouts.default')

@section('content')
    <div class="offset-md-2 col-md-8">
        <div class="card">
            <div class="card-header">注册本站</div>
            <div class="card-body">

                @include('shared._errors')

                <form method="POST" action="{{ route('user.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">用户名</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name" aria-describedby="name"
                               placeholder="请输入用户名">
                    </div>
                    <div class="form-group">
                        <label for="email">邮箱</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email" aria-describedby="email"
                               placeholder="请输入邮箱">
                        <small id="emailHelp" class="form-text text-muted">邮箱需要验证,请填写真实邮箱!</small>
                    </div>
                    <div class="form-group">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" id="password" placeholder="请输入密码">
                    </div>
                    <button type="submit" class="btn btn-primary">注册</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('register', 'active')