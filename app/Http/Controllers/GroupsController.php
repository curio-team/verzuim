<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \StudioKaa\Amoclient\Facades\AmoAPI;
use Carbon\Carbon;
use DB;

class GroupsController extends Controller
{
    public function index()
    {
        $user_id = \Auth::user()->id;
        $groups = AmoAPI::get('groups')->reject(function ($group) {
            return ($group["type"] != "class");
        });

        $favorites = DB::table('group_user')->where('user_id', $user_id)->get()->pluck('group_id');

        return view('groups.index')
                ->with(compact('favorites'))  
                ->with(compact('groups'));   
    }

    public function show($id)
    {
        $group = AmoAPI::get('groups/' . $id);
        $students = collect($group["users"]);
        $ids = $students->pluck("id")->map(function ($item) {
            return preg_replace("/[^0-9]/", "", $item);
        });

        //Get date of four weeks ago (counted from last monday)
        $date = Carbon::today()->startOfWeek()->subDays(\Auth::user()->weeks*7);

        $students = $this->ladder($date, $ids);

        return view('ladder.show')
                ->with(compact('students'))
                ->with(compact('group'))
                ->with('now', Carbon::today())
                ->with('then', $date);
    }

    public function favorite($id)
    {
        $user_id = \Auth::user()->id;
        $count = DB::table('group_user')->where([['user_id', $user_id], ['group_id', $id]])->count();
        if($count > 0)
        {
            DB::table('group_user')->where([['user_id', $user_id], ['group_id', $id]])->delete();
        }
        else
        {
            DB::table('group_user')->insert(['user_id' => $user_id, 'group_id' => $id]);
        }

        return redirect()->back();
    }

    public function home()
    {
        $user_id = \Auth::user()->id;
        $groups = DB::table('group_user')->where('user_id', $user_id)->orderBy('group_id')->get()->pluck('group_id');
        if($groups->count() < 1)
        {
            return redirect()->route('groups.index');
        }

        $date = Carbon::today()->startOfWeek()->subDays(\Auth::user()->weeks*7);
        $data = array();

        foreach ($groups as $group_id)
        {
            $group = AmoAPI::get('groups/' . $group_id);
            $students = collect($group["users"]);
            $ids = $students->pluck("id")->map(function ($item) {
                return substr($item, 1);
            });
            $data[] = array(
                "group" => $group,
                "students" => $this->ladder($date, $ids)
            );
        }

        #return $data;

        return view('ladder.home')
                ->with(compact('data'))
                ->with('now', Carbon::today())
                ->with('then', $date);
    }

    private function ladder($date, $ids)
    {
        $data = DB::select("SELECT SUM(duration) AS sum, COUNT(duration) AS count, student_id, student_name, type,
                                SUM(handled1) AS handled1, SUM(handled2) AS handled2, SUM(handled3) AS handled3, SUM(handled4) AS handled4, SUM(handled5) AS handled5
                            FROM logs
                            WHERE date > '{$date->format("Y-m-d")}' AND type <> 'Present' AND student_id IN ({$ids->implode(',')})
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

        return $students;
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
