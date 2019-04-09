<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 用户会话模块
 * Class AboutController
 * @package App\Http\Controllers
 */
class SessionController extends Controller
{
    /**
     * 创建会话视图(登陆页面)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function create()
    {
        if (Auth::check()){
            session()->flash('info', '您已登陆,无需重复操作');
            return redirect('/');
        }
        return view('session.create');
    }

    /**
     * 接收创建会话并认证(登陆)
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:100',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials, $request->has('remember'))){
            session()->flash('success', '欢迎回来!');
            return redirect()->route('index');
        }else{
            session()->flash('danger', '很抱歉,您的账号或密码不正确');
            return redirect()->back()->withInput();
        }
    }

    /**
     * 删除会话(登出)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功登出!');
        return redirect('login');
    }

}
