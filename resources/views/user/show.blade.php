@extends('layouts.default')
@section('title', $user->nickname)

@section('content')
    <div class="offset-md-2 col-md-8">
        <div class="card">
            <div class="card-header">用户信息</div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.update', $user->id) }}">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">用户名</label>
                        <input type="text" class="form-control" value="{{ $user->name }}"
                               id="name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">邮箱</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" id="email"
                               readonly>
                    </div>
                    <div class="form-group">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">确认密码</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                    </div>
                    <button type="submit" class="btn btn-primary">更新</button>
                </form>
            </div>
        </div>
    </div>
@stop