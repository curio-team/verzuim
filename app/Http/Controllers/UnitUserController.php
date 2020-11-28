<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\User;

class UnitUserController extends Controller
{
    public function index(Unit $unit)
    {
        return view('admin.units.users')
                ->with(compact('unit'))
                ->with('users', User::all());
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'users.*.active' => 'boolean',
            'users.*.member' => 'boolean',
            'users.*.coord' => 'boolean'
        ]);

        foreach($request->users as $id => $values)
        {
            $user = User::find($id);

            //Active
            $user->active = $values['active'];
            $user->save();

            //Coord
            $unit->users()->updateExistingPivot($id, [
                'coord' => $values['coord']
            ]);

            //Member
            if(!$values['member']) $unit->users()->detach($id);
        }

        return redirect()->back();
    }

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
