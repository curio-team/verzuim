<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Imports\LogsImport;
use App\Models\Log;

class ImportController extends Controller
{
    public function show()
    {
        return view('import.show');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);
        $date = Log::max('date') ?? "2000-01-01";
        Excel::import(new LogsImport($date), request()->file('file'));
        return redirect()->route('import.show')->with('status', 'Import uitgevoerd, status onbekend.');
    }
}
