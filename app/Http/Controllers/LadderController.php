<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \StudioKaa\Amoclient\Facades\AmoAPI;
use Carbon\Carbon;
use DB;
use App\Models\Unit;

class LadderController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if($user->login == "amoclient")
        {
            $groups = AmoAPI::get('groups')->reject(function ($group) {
                return ($group["type"] != "class");
            });
        }
        else
        {
            $start = Carbon::today()->startOfWeek()->subDays(7);
            $units = \Auth::user()->units->pluck('id');
            $groups = DB::table('logs')
                        ->select('group_name as name')
                        ->distinct()
                        ->whereIn('unit_id', $units)
                        ->whereBetween('date', [$start->format("Y-m-d"), Carbon::now()->format("Y-m-d")])
                        ->orderBy('group_name')
                        ->get('group_name');            
        }

        $groups = $groups->map(function ($item) {
            return collect($item);
        });
        
        $favorites = DB::table('group_user')->where('user_id', $user->id)->get()->pluck('group_name');
        return view('groups.index')
                ->with(compact('favorites'))  
                ->with(compact('groups'));   
    }

    public function show($name, Request $request)
    {
        $data = $this->getOneLadder($name, $request);
        $date = Carbon::today()->startOfWeek()->subDays(\Auth::user()->weeks*7);

        return view('ladder.show')
                ->with('students', $data[0])
                ->with('group', $name)
                ->with('now', Carbon::today())
                ->with('then', $date)
                ->with('last', $data[1])
                ->with('sickWeek', $data[2])
                ->with('sick3x', $data[3])
                ->with('sick5x', $data[4]);
    }

    public function favorite($name)
    {
        $user_id = \Auth::user()->id;
        $count = DB::table('group_user')->where([['user_id', $user_id], ['group_name', $name]])->count();
        if($count > 0)
        {
            DB::table('group_user')->where([['user_id', $user_id], ['group_name', $name]])->delete();
            $msg = ['danger' => 'Groep ' . $name . ' is niet langer favoriet.'];
        }
        else
        {
            DB::table('group_user')->insert(['user_id' => $user_id, 'group_name' => $name]);
            $msg = ['success' => 'Groep ' . $name . ' is nu als favoriet zichtbaar op de homepage.'];
        }

        return redirect()->back()->with('status', $msg);
    }

    public function home(Request $request)
    {
        $user = \Auth::user();
        $groups = DB::table('group_user')->where('user_id', $user->id)->orderBy('group_name')->get()->pluck('group_name');
        if($groups->count() < 1) return $this->index();

        $date = Carbon::today()->startOfWeek()->subDays(\Auth::user()->weeks*7);
        $data = array();

        foreach ($groups as $group_name)
        {
            // if($user->login == "amoclient")
            // {
            //     $group = AmoAPI::get('groups/find/' . $group_name);
            //     $students = collect($group["users"]);
            //     $ids = $students->pluck("id")->map(function ($item) {
            //         return preg_replace("/[^0-9]/", "", $item);
            //     });
            // }
            // else
            // {
            //     $start = Carbon::today()->startOfWeek()->subDays(7);
            //     $ids = DB::table('logs')
            //                 ->select('student_id')
            //                 ->distinct()
            //                 ->where('group_name', $group_name)
            //                 ->whereBetween('date', [$start->format("Y-m-d"), Carbon::now()->format("Y-m-d")])
            //                 ->get('student_id')
            //                 ->pluck('student_id');
            // }

            // $unit = DB::table('logs')
            //         ->select('unit_id')
            //         ->where('group_name', $group_name)
            //         ->first();
            // $unit = Unit::find($unit->unit_id);
            // $last = ImportController::getDate($unit);
            // $last = $last ? $last->formatLocalized('%a %e %b') : "(nog nooit)";

            $oneGroup = $this->getOneLadder($group_name, $request);

            $data[] = array(
                "group" => $group_name,
                "students" => $oneGroup[0],
                "last" => $oneGroup[1],
                "sickWeek" => $oneGroup[2],
                "sick3x" => $oneGroup[3],
                "sick5x" => $oneGroup[4]
            );
        }
        
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

            // Threshold = de ondergrens waarBOVEN het niet meer oké is

            // $thresholdsZ = array(
            //     5 => ["num" => 8,   "func" => "count"],
            //     4 => ["num" => 5,   "func" => "count"],
            //     3 => ["num" => 3,   "func" => "count"],
            //     2 => ["num" => 2,   "func" => "count"],
            //     1 => ["num" => 1,   "func" => "count"],
            // );
            // $this->process("Ziek", $students, $name, $nums, $thresholdsZ);

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

        // dd($students);

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
                    if($students[$name]["step"] < $i)
                    {
                        $students[$name]["step"] = $i;
                        $students[$name]["reason"] = $type;
                    }

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

    private function getOneLadder($name, $request)
    {
        $user = \Auth::user();
        if($user->login == "amoclient")
        {
            $group = AmoAPI::get('groups/find/' . $name);
            $students = collect($group["users"]);
            if(count($students) < 1)
            {
                return redirect()->back()->with('status', ['danger' => 'Groep ' . $name . ' bevat geen studenten!']);
                exit();
            }

            $ids = $students->pluck("id")->map(function ($item) {
                return preg_replace("/[^0-9]/", "", $item);
            });
        }
        else
        {
            $start = Carbon::today()->startOfWeek()->subDays(7);
            $ids = DB::table('logs')
                        ->select('student_id')
                        ->distinct()
                        ->where('group_name', $name)
                        ->whereBetween('date', [$start->format("Y-m-d"), Carbon::now()->format("Y-m-d")])
                        ->get('student_id')
                        ->pluck('student_id');
        }

        $unit = DB::table('logs')
                    ->select('unit_id')
                    ->where('group_name', $name)
                    ->first();
        $unit = Unit::find($unit->unit_id);
        $last = ImportController::getDate($unit);
        $last = $last ? $last->formatLocalized('%a %e %b') : "(nog nooit)";

        $date = Carbon::today()->startOfWeek()->subDays(\Auth::user()->weeks*7);
        $students = $this->ladder($date, $ids);

        //
        // ZIEK: vind studenten die meer dan een week ziek zijn
        $sickWeekRaw = DB::select("SELECT student_id, student_name, type, MAX(date) as max_date FROM logs WHERE student_id IN ({$ids->implode(',')}) GROUP BY student_id, student_name, type ORDER BY student_name, student_id, max_date DESC, type DESC");

        $sickWeekRaw = collect($sickWeekRaw)->groupBy('student_id');
        $sickWeek = collect();
        $sevenDaysAgo = Carbon::today()->subDays(7);

        foreach($sickWeekRaw as $studentRaw)
        {
            $student = $studentRaw->first();
            $maxdate = Carbon::createFromFormat("Y-m-d H:i", $student->max_date . " 00:00"); 

            if($student->type == "Ziek" && $maxdate <= $sevenDaysAgo)
            {
                $sickWeek->push($student);
            }
        }

        // Als het schooljaar pas net is begonnen, kijk dan niet 18 weken terug maar alleen begin schooljaar
        $now = Carbon::now();
        $year = $now->year;
        if($now->month <= 7) $year -= 1;
        $startOfSchoolYear = Carbon::createMidnightDate($year, 8, 1);

        //
        // ZIEK: vind studenten die meer dan 3x ziek waren in 8 weken
        $dateSick3x = Carbon::today()->startOfWeek()->subDays(8*7);
        if($startOfSchoolYear > $dateSick3x) $dateSick3x = $startOfSchoolYear;
        if($request->filled('start'))
        {
            $dateSick3x = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        }

        $sick3x = collect(DB::select("SELECT student_id, student_name, type, COUNT(duration) AS count
                            FROM logs
                            WHERE date > '{$dateSick3x->format('Y-m-d')}' AND student_id IN ({$ids->implode(',')}) AND type = 'Ziek'
                            GROUP BY student_id, student_name, type
                            HAVING count > 3"));

        //
        // ZIEK: vind studenten die meer dan 5x ziek waren in 18 weken
        $dateSick5x = Carbon::today()->startOfWeek()->subDays(18*7);
        if($startOfSchoolYear > $dateSick5x) $dateSick5x = $startOfSchoolYear;
        if($request->filled('start'))
        {
            $dateSick5x = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        }
        
        $sick5x = collect(DB::select("SELECT student_id, student_name, type, COUNT(duration) AS count
                            FROM logs
                            WHERE date > '{$dateSick5x->format('Y-m-d')}' AND student_id IN ({$ids->implode(',')}) AND type = 'Ziek'
                            GROUP BY student_id, student_name, type
                            HAVING count > 5"));

        $sick5xIds = $sick5x->pluck('student_id');
        $sick3x = $sick3x->reject(function($item) use($sick5xIds){
            return $sick5xIds->contains($item->student_id);
        });

        return [$students, $last, $sickWeek, $sick3x, $sick5x];
    }
}
