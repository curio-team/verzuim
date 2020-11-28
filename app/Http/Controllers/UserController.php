<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index')
                ->with(compact('users'));
    }

    public function edit(User $user)
    {
        $units = Unit::all();
        return view('admin.users.edit')
                ->with(compact('user'))
                ->with(compact('units'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'active' => 'required|boolean',
            'admin' => 'required|boolean',
            'units.*.member' => 'boolean',
            'units.*.coord' => 'boolean'
        ]);

        $user->name = $request->name;
        $user->active = $request->active;
        $user->admin = $request->admin;
        $user->save();

        foreach($request->units as $id => $values)
        {
            $user->units()->updateExistingPivot($id, [
                'coord' => $values['coord']
            ]);

            if(!$values['member']) $user->units()->detach($id);
        }

        return redirect()->back()->with('status', ['info' => "Gebruiker {$user->id} opgeslagen!"]);
    }

    public function destroy(User $user)
    {
        $code = $user->id;
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', ['danger' => "Gebruiker $code verwijderd!"]);
    }
}
