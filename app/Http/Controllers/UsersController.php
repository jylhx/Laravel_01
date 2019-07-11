<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except'=>['register','store','show']]);
        $this->middleware('guest',['only'=>['register']]);
    }
    //
    public function  register(){
        return view('users/register');
    }

    //显示用户基本信息
    // http://laravel01.test/users/1
    //  | GET|HEAD  | users/{user}  隐性路由模型绑定
    // 使用 Eloquent 模型的单数小写格式来作为路由片段参数
    public function show(User $user){
        return view('users/show',compact('user'));
    }

    /**
     * 注册
     * @param Request $request
     */
    public function store(Request $request){

        $this->validate($request,[
            'name'=>'required|max:30',
            'email'=>'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user=User::create(
            [ 'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]
        );
        //自动登录
        Auth::login($user);
        session()->flash('success', '注册成功');
        return redirect()->route('users.show',[$user]);
    }

    public function edit(User $user)
    {
        $this->authorize('own',$user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user,Request $request){
        $this->authorize('own',$user);
        $this->validate($request,[
            'name'=>'required|min:3',
            'password'=>'nullable|confirmed|min:6',
        ]);
        $data['name']=$request->name;
        if($request->password){
            $data['password']=bcrypt($request->passwrord);
        }
        $user->update($data);
        session()->flash('success','编辑资料成功');
        return redirect()->route('users.show',$user);
    }


}
