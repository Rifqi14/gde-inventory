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
