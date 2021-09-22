<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Imports\LogsImport;
use App\Models\Unit;
use App\Models\Log;
use Carbon\Carbon;
use DB;

class ImportController extends Controller
{
    public function home()
    {
        $unit = \Auth::user()->my_import_units[0];
        return redirect()->route('import.show', $unit);
    }

    public function show(Unit $unit)
    {
        $last = Log::where('unit_id', $unit->id)->max('date');
        $last = $this->getDate($unit);
        $last = $last ? $last->format('d-m-Y') : "(nog nooit)";
        return view('import.show')
                ->with(compact('last'))
                ->with(compact('unit'));
    }

    public function upload(Request $request, Unit $unit)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        if(!\Auth::user()->my_import_units->pluck("id")->contains($unit->id))
        {
            return redirect()->back()->with('status', ['warning' => "Geen toestemming om voor deze afdeling te importeren."]);
        }

        $dateLower =  $this->getDate($unit, "2000-01-01");
        $dateUpper = Carbon::yesterday();

        $count1 = DB::table("logs")->count();
        Excel::import(new LogsImport($dateLower, $dateUpper, $unit->id), request()->file('file'));
        $count2 = DB::table("logs")->count();
        $diff = $count2 - $count1;

        if($diff > 0) $msg = ["success" => "Import geslaagd,  $diff nieuwe registraties toegevoegd."];
        else $msg = ["warning" => "Er zijn geen nieuwe registraties geïmporteerd."];
        return redirect()->route('import.show', $unit)->with('status', $msg);
    }




    private function getDate($unit, $default = null)
    {
        $today = Carbon::today();
        $date = Log::where('unit_id', $unit->id)->max('date');
        if($date == null)
        {
            if($default == null) return null;
            return new Carbon($default);
        }

        $date = Carbon::createFromFormat("!Y-m-d", $date);
        if($date->greaterThanOrEqualTo(Carbon::today())) return $today;

        return $date;
    }
}
