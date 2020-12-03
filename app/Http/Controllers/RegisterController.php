<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use DB;
use Str;


class RegisterController extends Controller
{
    public function form()
    {
        $units = Unit::orderBy('name')->get();
        return view('auth.register')
                ->with(compact('units'));
    }

    public function do(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|ends_with:@curio.nl|unique:users',
            'password'  => 'required|confirmed|between:6,1000',
            'code'      => 'required|between:4,10|unique:users,id',
            'unit'      => 'required'
        ]);
        
        $user = new User();
        $user->id = $request->code;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = 'teacher';
        $user->login = 'internal';
        $user->password = Hash::make($request->password);
        $user->active = -1;
        $user->save();

        $user->units()->attach($request->unit);

        return redirect()->back()->with('status', ['success' => 'Account aangevraagd - contacteer je teamcoordinator voor goedkeuring.']);
    }
}
