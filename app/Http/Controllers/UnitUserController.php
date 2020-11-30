<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\User;

class UnitUserController extends Controller
{
    public function sync_unit(Request $request, Unit $unit)
    {
        $unit->users()->sync($request->users);
        return redirect()->back();
    }

    public function sync_user(Request $request, User $user)
    {
        $user->units()->sync($request->units);
        return redirect()->back();
    }
}
