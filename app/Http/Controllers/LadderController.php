<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LadderController extends Controller
{
    public function show()
    {

        //Get date of four weeks ago (counted from last monday)
        $date = Carbon::today()->startOfWeek()->subDays(28);

        //Query
        $data = DB::select("SELECT SUM(duration) AS sum, COUNT(duration) AS count, student_id, student_name, type,
                                SUM(handled1) AS handled1, SUM(handled2) AS handled2, SUM(handled3) AS handled3, SUM(handled4) AS handled4, SUM(handled5) AS handled5
                            FROM logs
                            WHERE date > '{$date->format("Y-m-d")}' AND type <> 'Present'
                            GROUP BY student_id, student_name, type");
        
        //Groepeer op student
        $data = collect($data)->groupBy('student_name');

        //Stel registratie-types in als keys
        $data = $data->map(function ($item, $key) {
            return $item->keyBy('type');
        });

        
        $students = array();
        foreach($data as $name => $nums)
        {
            $students[$name]["step"] = 0;
            $students[$name]["reason"] = null;
            $students[$name]["handled"] = array(1 => false, 2 => false, 3 => false, 4 => false, 5 => false);

            $thresholdsRA = array(
                5 => ["num" => 100, "func" => "count"],
                4 => ["num" => 100, "func" => "count"],
                3 => ["num" => 4,   "func" => "count"],
                2 => ["num" => 2,   "func" => "count"],
                1 => ["num" => 1,   "func" => "count"],
            );
            $this->process("Regulier absent", $students, $name, $nums, $thresholdsRA);

            $thresholdsTL = array(
                5 => ["num" => 12, "func" => "count"],
                4 => ["num" => 8,  "func" => "count"],
                3 => ["num" => 4,  "func" => "count"],
                2 => ["num" => 3,  "func" => "count"],
                1 => ["num" => 1,  "func" => "count"],
            );
            $this->process("Te laat", $students, $name, $nums, $thresholdsTL);

            $thresholdsOA = array(
                5 => ["num" => 16, "func" => "sum"],
                4 => ["num" => 12, "func" => "sum"],
                3 => ["num" => 2,  "func" => "count"],
                2 => ["num" => 1,  "func" => "count"],
                1 => ["num" => 0,  "func" => "count"],
            );
            $this->process("Absent", $students, $name, $nums, $thresholdsOA);
        }

        return view('ladder.show')
                ->with(compact('students'))
                ->with('now', Carbon::today())
                ->with('then', $date);
    }

    private function process($type, &$students, $name, $nums, $thresholds)
    {
        //Als dit type registratie aanwezig is voor de student...
        if($nums->has($type))
        {
            //Pak dan de id van student uit deze array
            $students[$name]["id"] = $nums[$type]->student_id;

            //Ga alle vijf de fases af (van 5 -> 1)
            for($i = 5; $i >= 1; $i--)
            {
                $func = $thresholds[$i]["func"];
                $num = $thresholds[$i]["num"];
                $handled = "handled" . $i;

                //Als het aantal hoger is dan threshold
                if($nums[$type]->$func > $num)
                {
                    $students[$name]["step"] = $i;
                    $students[$name]["reason"] = $type;

                    //Als het aantal afgehandelde registraties gelijk is aan het aantal "verzoorzakende" registraties
                    if($nums[$type]->$handled >= $nums[$type]->count)
                    {
                        $students[$name]["handled"][$i] = true;
                    }

                    //Student zit voor dit type in een fase, stop met zoeken
                    return; 
                }
            }
        }
    }

}
