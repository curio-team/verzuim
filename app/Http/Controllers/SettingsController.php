<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class SettingsController extends Controller
{
    public function show()
    {
        $user = \Auth::user();
        return view('settings.show')
                ->with(compact('user'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'password'  => 'sometimes|confirmed|between:6,1000'
        ]);

        $user = User::find(\Auth::user()->id);
        $user->weeks = $request->weeks;
        if($request->password)
        {
            $user->password = Hash::make($request->password);
            $user->password_once = false;
        }
        $user->save();
        return redirect()->back()->with("status", ["success" => "Instellingen opgeslagen!"]);
    }
}
