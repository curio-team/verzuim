<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('admin.units.index')
                ->with(compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request, Unit $unit)
    {
        $request->validate([
            'unit' => 'required'
        ]);
        $unit = new Unit();
        $unit->name = $request->unit;
        $unit->save();
        return redirect()->route('admin.units.index')->with('status', ['success' => "Afdeling {$unit->name} aangemaakt!"]);
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit')
                ->with(compact('unit'))
                ->with('users', User::all());
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit' => 'required',
            'users.*.member' => 'boolean',
            'users.*.importer' => 'boolean',
            'users.*.coord' => 'boolean'
        ]);

        $unit->name = $request->unit;
        $unit->save();

        foreach($request->users as $id => $values)
        {
            $unit->users()->updateExistingPivot($id, ['coord' => $values['coord']]);
            $unit->users()->updateExistingPivot($id, ['importer' => $values['importer']]);
            if(!$values['member']) $unit->users()->detach($id);
        }

        return redirect()->back()->with('status', ['info' => "Afdeling {$unit->name} opgeslagen!"]);
    }

    public function destroy(Unit $unit)
    {
        $name = $unit->name;
        $unit->delete();
        return redirect()->route('admin.units.index')->with('status', ['danger' => "Afdeling $name verwijderd!"]);
    }
}
