<?php

namespace App\Http\Controllers;

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
        $user = User::find(\Auth::user()->id);
        $user->weeks = $request->weeks;
        $user->save();
        return redirect()->back()->with("status", "Aangepast");
    }
}
