<?php

namespace App\Imports;

use App\Models\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;


class LogsImport implements ToModel, WithHeadingRow
{
    private $check_date = null;
    private $unit_id = null;

    public function __construct($date, $unit)
    {
        $this->check_date = $date;
        $this->unit_id = $unit;
    }

    public function model(array $row)
    {
        $date = Carbon::createFromFormat("!d-m-Y", $row['datumvan']);
        if($date->lessThanOrEqualTo($this->check_date)) return;
        
        $duration = ($row['lesuureind'] - $row['lesuurbegin'] + 1) / 2;

        return new Log([
            'unit_id' => $this->unit_id,
            'date' => $date->format('Y-m-d'),
            'student_id' => $row['ovnr'],
            'student_name' => $row['deelnemernaam'],
            'group_name' => $row['groepnaam'],
            'type' => $row['typeomschrijving'],
            'hour_start' => $row['lesuurbegin'],
            'hour_end' => $row['lesuureind'],
            'duration' => $duration,
            'time_start' => $row['tijdvan2'],
            'time_end' => $row['tijdtot2'],
            'duration_text' => $row['tijd'],
            'reason' => $row['reden'],
            'comment' => $row['opmerking'],
            'logged_by' => $row['naam']
        ]);
    }
}
