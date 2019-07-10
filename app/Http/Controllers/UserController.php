<?php

namespace App\Http\Controllers;

use App\User;
use App\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('login');
    }

    public function index()
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        return view('user.home', compact('username', 'roles'));
    }

    public function update($user, $name)
    {
        $access = Input::all('access');
        $users = User::where('username', $name)->first();
        foreach ($access as $access) {
            $users->access()->sync($access);
        }
        return redirect()->route('admin', $user)->with('msg', 'User Access is Updated');
    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/');
    }

    public function show($user)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $users = User::all();
        $title = (last(request()->segments()));
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        return view('user.index', compact('username', 'roles', 'users', 'title'));
    }

    public function login()
    {
        $this->validation();
        if ($this->checkData()) {
            $username = request('username');
            $user = User::where('username', $username)->firstorFail();
            \Auth::login($user, true);
            return redirect()->action('UserController@index', $user->username);
        } else {
            return redirect()->back()->withInput(request()->only('username'))->withErrors(['msg' => 'Username atau Password anda salah']);
        }
    }

    public function validation()
    {
        $data = request()->validate(
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'Harap masukkan username anda.',
                'password.required' => 'Harap masukkan password anda.',
            ]
        );

        return $data;
    }

    public function checkData()
    {
        $username = request('username');
        $password = request('password');
        // dd(\Hash::make($password));
        $login = User::where('username', $username)->first();
        if ($login != NULL)
            return (password_verify($password, $login->password)) ? TRUE : FALSE;
        else
            return FALSE;
    }

    public function register($user)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        return view('user.register', compact('username', 'roles'));
    }

    public function store($user)
    {
        $user = \Auth::user();
        if ($this->validateRegister()) {
            $newuser = new User;
            $newuser->username = request('username');
            $newuser->password = \Hash::make(request('password'));
            $newuser->save();
            return redirect()->action('UserController@show', $user->username)->with('msg', 'User Registered Successfully');
        }
    }

    public function validateRegister()
    {
        $data = request()->validate(
            [
                'username' => 'required|unique:users',
                'password' => 'required|min:6|same:password_confirmation',
                'password_confirmation' => 'same:password'
            ],
            [
                'username.required' => 'Harap masukkan username anda.',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Harap masukkan password anda.',
                'password.min' => 'Minimal 6 karakter',
                'password.same' => 'Silahkan ulangi password anda',
                'password_confirmation.same' => 'Password harus sama dengan password diatas'
            ]
        );
        return $data;
    }
    public function edit($user, $name)
    {
        $access = Access::all();
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $useraccess = User::where('username', $name)->first();
        $useraccess = $useraccess->access()->get();
        $usergranted = [];
        foreach ($useraccess as $useraccess) {
            array_push($usergranted, $useraccess->id);
        }
        $count = count($usergranted);
        return view('user.access', compact('access', 'roles', 'username', 'name', 'usergranted', 'count'));
    }

    public function accesslist()
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $title = (last(request()->segments()));
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        $access = Access::all();
        return view('user.accesslist', compact('username', 'roles', 'access', 'title'));
    }
}
