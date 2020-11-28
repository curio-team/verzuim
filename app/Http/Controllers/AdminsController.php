<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AdminsController extends Controller
{
    public function show()
    {
        $admins = DB::table('admins')->get();
        return view('admins.show')
                ->with(compact('admins'));
    }

    public function save(Request $request)
    {
        $request->validate([
            "code" => "required"
        ]);
        
        DB::table('admins')->insert([
            "user_id" => $request->code,
            "created_by" => \Auth::user()->id
        ]);

        return redirect()->back()->with('status', ['success' => 'Gebruiker ' . $request->code . ' toegevoegd als admin!']);
    }

    public function delete(Request $request, $code)
    {
        DB::table('admins')->where('user_id', $code)->delete();
        return redirect()->back()->with('status', ['danger' => 'Gebruiker ' . $request->code . ' verwijderd als admin!']);
    }
}
