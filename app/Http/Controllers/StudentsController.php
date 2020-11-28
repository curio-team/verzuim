<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use \StudioKaa\Amoclient\Facades\AmoAPI;

class StudentsController extends Controller
{

    public function me()
    {
        $user = \Auth::user();
        $id = preg_replace("/[^0-9]/", "", $user->id);
        return $this->show($id, $user->toArray());
    }

    public function show($id, $student = null)
    {
        if($student == null)
        {
            try{
                $student = AmoAPI::get('users/i' . $id);
            }
            catch(\GuzzleHttp\Exception\ClientException $e){
                $student = AmoAPI::get('users/D' . $id);
            }
        }
        
        $date = Carbon::today()->startOfWeek()->subDays(\Auth::user()->weeks*7);
        $logs = DB::select("SELECT *
                            FROM logs
                            WHERE date > ? AND type <> 'Present' AND student_id = ?
                            ORDER BY date, hour_start", [$date->format("Y-m-d"), $id]);
        $logs = collect($logs);
        
        $present = DB::select("SELECT SUM(duration) AS total
                            FROM logs
                            WHERE date > ? AND type = 'Present' AND student_id = ?"
                            , [$date->format("Y-m-d"), $id]);
        $present = $present[0]->total ?? 0;

        if(count($logs)) $view = "ladder.student";
        else $view = "ladder.empty";
        return view($view)
                ->with(compact('logs'))
                ->with(compact('present'))
                ->with(compact('student'))
                ->with('now', Carbon::today())
                ->with('then', $date);
    }

    public function handle($id, $step, $reason)
    {
        $date = Carbon::today()->startOfWeek()->subDays(\Auth::user()->weeks*7);

        DB::statement("UPDATE logs
                            SET handled$step = 1
                            WHERE date > ? AND student_id = ? AND type = ?
                            ORDER BY date, hour_start", [$date->format("Y-m-d"), $id, $reason]);

        return redirect()->back();
    }
}
