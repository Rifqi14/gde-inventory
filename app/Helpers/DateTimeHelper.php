<?php
if (!function_exists('hoursToMinutes')) {

    function hoursToMinutes($hours)
    {
        $minutes = 0;
        if ($hours) {
            if (strpos($hours, ':') !== false) {
                // Split hours and minutes. 
                list($hours, $minutes) = explode(':', $hours);
            }
        } else {
            $hours = 0;
        }
        return $hours * 60 + $minutes;
    }
}

if (!function_exists('minutesToHours')) {

    function minutesToHours($minutes)
    {
        $hours = (int)($minutes / 60);
        $minutes -= $hours * 60;
        return sprintf("%d:%02.0f", $hours, $minutes);
    }
}

if (!function_exists('convertTime')) {
    function convertTime($dec)
    {
        // start by converting to seconds
        $seconds = ($dec * 3600);
        // we're given hours, so let's get those the easy way
        $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        // remove those from seconds as well
        $seconds -= $minutes * 60;
        // return the time formatted HH:MM:SS
        return lz($hours) . ":" . lz($minutes);
    }
    // lz = leading zero
    function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }
}

if (!function_exists('monthThree')) {

    function monthThree($month)
    {
        $m = [
            1 => "Jan",
            2 => "Feb",
            3 => "Mar",
            4 => "Apr",
            5 => "May",
            6 => "Jun",
            7 => "Jul",
            8 => "Aug",
            9 => "Sep",
            10 => "Oct",
            11 => "Nov",
            12 => "Dec",
        ];
        return $m[$month];
    }
}

if (!function_exists('formatHour')) {
    function formatHour($time)
    {
        $format = '';
        if ($time) {
            $new = explode(':', $time);
            $format = $new[0] . ":" . $new[1];
        }
        return $format;
    }
}

if (!function_exists('changeDateFormat')) {
    function changeDateFormat($format, $date)
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('changeSlash')) {
    function changeSlash($date)
    {
        return str_replace('/', '-', $date);
    }
}

if (!function_exists('standardDate')) {
    function standardDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}

if (!function_exists('dbDate')) {
    function dbDate($date)
    {
        $date = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($date));
    }
}

if (!function_exists('countWorkingTime')) {
    function countWorkingTime($start = null, $finish = null)
    {
        $in = $start ? Carbon::parse($start) : null;
        $out = $finish ? Carbon::parse($finish) : null;
        $mins = $in && $out ? $in->diffInMinutes($out) : 0;
        return $mins / 60;
    }
}

if (!function_exists('countOverTime')) {
    function countOverTime($start = null, $finish = null)
    {
        $overtime = $start && $finish ? (new Carbon($finish))->diffInMinutes(new Carbon($start)) : 0;
        return $overtime / 60;
    }
}

if (!function_exists('dateInAMonth')) {
    function dateInAMonth($month, $year)
    {
        $dates = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $amonth = [];
        for ($i = 1; $i <= $dates; $i++) {
            $amonth[] = changeDateFormat('Y-m-d', $year . '-' . $month . '-' . $i);
        }

        return $amonth;
    }
}

if (!function_exists('countDateDiff')) {
    function countDateDiff($start, $end)
    {
        $date1 = date_create($start);
        $date2 = date_create($end);
        return date_diff($date1, $date2, true);
    }
}