<?php
if (!function_exists('DateRangeHelper')) {
   
    function getDatesFromRange($start, $end, $format = 'Ym', $routine)
    {
        $array = array();
        $interval = \DateInterval::createFromDateString("$routine");

        $realEnd = new \DateTime($end);
        $realEnd->add($interval);

        $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date) { 
            if($date <= new DateTime($end)){
                $array[] = $date->format($format); 
            }
        }

        return $array;
    }
}