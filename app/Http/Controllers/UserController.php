<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * 创建用户表单 (注册页面)
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            session()->flash('info', '您已登陆,无需重复操作');
            return redirect('/');
        }
        return view('user.create');
    }

    /**
     * 注册接收页面
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('user.show', $user);
    }

    /**
     * 显示用户个人中心 (个人中心|修改密码)
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->owner($user->id);
        return view('user.show', compact('user'));
    }

    /**
     * 处理用户更新请求
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $this->owner($user->id);
        $this->validate($request, [
            'password' => 'required|confirmed|min:8'
        ]);
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->get('password'));
            $user->update($data);
            session()->flash('success', '个人资料更新成功!');
        } else {
            session()->flash('info', '未填写更新内容');
        }
        return redirect()->route('user.show', $user);
    }

    /**
     * 判断请求的是否是当前用户或管理员
     * @param $id
     * @return void
     */
    private function owner($id)
    {
        if (Auth::user()->role_id !== 1 && Auth::id() !== $id) {
            abort(403, '无权查看');
        }
    }
}
