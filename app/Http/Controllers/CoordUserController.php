<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;

class CoordUserController extends Controller
{
    public function index()
    {
        $myUnits = \Auth::user()->my_coord_units;
        $users = User::whereHas('units', function (Builder $query) use($myUnits) {
            $query->whereIn('units.id', $myUnits->pluck("id"));
        })->get();

        return view('coord.users.index')
                ->with('units', $myUnits)
                ->with(compact('users'));
    }

    public function requests()
    {
        $myUnits = \Auth::user()->my_coord_units;
        $users = User::whereHas('units', function (Builder $query) use($myUnits) {
            $query->whereIn('units.id', $myUnits->pluck("id"));
        })->where('active', -1)->get();

        return view('coord.users.requests')
                ->with(compact('users'));
    }

    public function edit(User $user)
    {
        if($this->cant_edit($user))
        {
            return redirect()->route('coord.users.index')->with('status', ['warning' => "Gebruiker {$user->id} valt niet onder een afdeling waar van jij coordinator bent."]);
        }

        return view('coord.users.edit')
                ->with('myUnits', \Auth::user()->my_coord_units)
                ->with(compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'units.*.member' => 'boolean',
            'units.*.importer' => 'boolean',
            'units.*.coord' => 'boolean'
        ]);
        
        $myUnits = \Auth::user()->my_coord_units->pluck('id');
        $units = $request->units ?? array();
        foreach($units as $id => $values)
        {
            if($myUnits->contains($id))
            {
                $user->units()->updateExistingPivot($id, ['coord' => $values['coord']]);
                $user->units()->updateExistingPivot($id, ['importer' => $values['importer']]);
                if(!$values['member']) $user->units()->detach($id);
            }
        }

        return redirect()->back()->with('status', ['info' => "Gebruiker {$user->id} opgeslagen!"]);
    }

    public function reset(User $user)
    {
        $password = $this->readable_random();
        $user->password_once = true;
        $user->password = Hash::make($password);
        $user->save();
        return redirect()->back()->with('password', $password);
    }

    public function activate(User $user)
    {
        if($this->cant_edit($user))
        {
            return redirect()->back()->with('status', ['warning' => "Gebruiker {$user->id} valt niet onder een afdeling waar van jij coordinator bent."]);
        }

        $user->active = true;
        $user->save();
        return redirect()->back()->with('status', ['success' => "Gebruiker {$user->id} is geactiveerd."]);
    }

    public function deny(User $user)
    {
        if($this->cant_edit($user))
        {
            return redirect()->back()->with('status', ['warning' => "Gebruiker {$user->id} valt niet onder een afdeling waar van jij coordinator bent."]);
        }

        $user->delete();
        return redirect()->back()->with('status', ['success' => "Gebruiker {$user->id} is verwijderd."]);
    }

    public function units(Request $request)
    {
        $request->validate([
            "code" => "required",
            "unit" => "required"
        ]);

        if(!\Auth::user()->my_coord_units->pluck("id")->contains($request->unit))
        {
            return redirect()->back()->with('status', ['warning' => "Geen toestemming om aan deze afdeling toe te voegen."]);
        }

        if(!User::find($request->code))
        {
            return redirect()->back()->with('status', ['warning' => "Gebruiker {$request->code} niet gevonden."]);
        }

        $unit = Unit::find($request->unit);
        $unit->users()->attach($request->code);
        return redirect()->back()->with('status', ['success' => "Gebruiker {$request->code} is toegevoegd aan {$unit->name}."]);
    }






    private function cant_edit($user)
    {
        return !$this->can_edit($user);
    }

    private function can_edit($user)
    {
        $myUnits = \Auth::user()->my_coord_units;
        $intersect = $myUnits->intersect($user->units);
        return ($intersect->count() > 0) ? true : false;
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
