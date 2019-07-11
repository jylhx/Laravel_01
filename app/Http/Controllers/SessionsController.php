<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class SessionsController extends Controller {

    public function __construct(){
        $this->middleware('auth',['only'=>['destroy']]);
        $this->middleware('guest',['only'=>['create','store']]);
    }
    /**
     * 显示登录界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('users.login');
    }

    /**
     * 处理登录
     * @param Request $request
     */
    public function store(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->has('remember'))) {
            //校验登录
            session()->flash('success', '欢迎回来！');
            return redirect()->route('users.show', [Auth::user()]);
        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    public function destroy() {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
