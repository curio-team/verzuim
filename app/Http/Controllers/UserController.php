<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function reset(User $user)
    {
        $password = $this->readable_random();
        $user->password_once = true;
        $user->password = Hash::make($password);
        $user->save();
        return redirect()->back()->with('password', $password);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'active' => 'required|boolean',
            'admin' => 'required|boolean',
            'units.*.member' => 'boolean',
            'units.*.importer' => 'boolean',
            'units.*.coord' => 'boolean'
        ]);

        $user->name = $request->name;
        $user->active = $request->active;
        $user->admin = $request->admin;
        $user->save();
        
        $units = $request->units ?? array();
        foreach($units as $id => $values)
        {
            $user->units()->updateExistingPivot($id, ['coord' => $values['coord']]);
            $user->units()->updateExistingPivot($id, ['importer' => $values['importer']]);
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

    private function readable_random($length = 6)
    {
      $conso = array("b","c","d","f","g","h","j","k","l","m","n","p","r","s","t","v","w","x","y","z");
      $vocal = array("a","e","i","o","u");
  
      $password = "";
      srand ((double)microtime()*1000000);
      $max = $length / 2;
  
      for($i = 1; $i <= $max; $i++)
      {
        $password .= $conso[rand(0,19)];
        $password .= $vocal[rand(0,4)];
      }
  
      return $password;
    }
}
