<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{

    public function form()
    {
        return view('auth.login');
    }

    public function do(Request $request)
    {
        $request->validate([
            'email'     => 'required',
            'password'  => 'required',
            'remember'  =>  'sometimes|boolean'
        ]);

        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'id';

        $is_authenticated = Auth::attempt(
            [$field => $request->email, 'password' => $request->password, 'active' => true],
            $request->remember ?? false
        );

        if($is_authenticated)
        {
            return redirect()->intended('/');
        }

        return redirect()->back()->with('status', ['warning' => 'Account niet gevonden of niet actief.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
