<?php

namespace App\Http\Controllers;

use App\Models\Unit;
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
        return view('admin.units.form')
                ->with('unit', new Unit());
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
        return view('admin.units.form')
                ->with(compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit' => 'required'
        ]);
        $unit->name = $request->unit;
        $unit->save();
        return redirect()->route('admin.units.index')->with('status', ['info' => "Afdeling {$unit->name} opgeslagen!"]);
    }

    public function destroy(Unit $unit)
    {
        $name = $unit->name;
        $unit->delete();
        return redirect()->route('admin.units.index')->with('status', ['danger' => "Afdeling $name verwijderd!"]);
    }
}
