<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Str;


class RegisterController extends Controller
{
    public function form()
    {
        return view('auth.register');
    }

    public function do(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|ends_with:@curio.nl|unique:users',
            'password'  => 'required|confirmed|between:6,1000'
        ]);
        
        $id = Str::uuid();
        while(DB::table('users')->where('id', $id)->count())
        {
            $id = Str::uuid();
        }

        $user = new User();
        $user->id = $id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = 'teacher';
        $user->login = 'internal';
        $user->password = Hash::make($request->password);
        $user->active = false;
        $user->save();

        return redirect()->back()->with('status', ['success' => 'Account aangevraagd - wacht op goedkeuring.']);
    }
}
