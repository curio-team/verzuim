<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Imports\LogsImport;
use App\Models\Log;
use Carbon\Carbon;
use DB;

class ImportController extends Controller
{
    public function show()
    {
        $last = Log::max('date');
        $last = $last ? (new Carbon($last))->format("d-m-Y") : "(nog nooit)";
        return view('import.show')
                ->with(compact('last'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        $count1 = DB::table("logs")->count();
        $date = Log::max('date') ?? "2000-01-01";
        Excel::import(new LogsImport($date), request()->file('file'));
        $count2 = DB::table("logs")->count();
        $diff = $count2 - $count1;

        if($diff > 0) $msg = ["success" => "Import geslaagd,  $diff nieuwe registraties toegevoegd."];
        else $msg = ["warning" => "Er zijn geen nieuwe registraties geïmporteerd."];
        return redirect()->route('import.show')->with('status', $msg);
    }
}
