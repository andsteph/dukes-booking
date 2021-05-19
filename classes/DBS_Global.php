<?php

class DBS_Global
{
    static $timezone = 'America/Toronto';
    static $starttime;
    static $endtime;
    static $blocktime = 30 * 60;
    static $buffertime = 5 * 60;    
    static $valid_times = [];
    static $block_price = 30;
    static $extra_block_discount = 0.5;

    // get years ------------------------------------------
    static function get_years()
    {
        $years = [];
        $start_year = date('Y');
        $years_to_show = 10;
        for ( $i=0; $i<$years_to_show; $i++ ) {
            $year = $start_year + $i;
            array_push($years, $year);
        }
        return $years;
    }

    // check to see if time is valid ----------------------
    static function is_valid_time($time)
    {
        foreach (DBS_Global::$valid_times as $valid_time) {
            if ($time == $valid_time) {
                return true;
            }
        }
        return false;
    }
    
}

DBS_Global::$starttime = strtotime('10:00'); 
DBS_Global::$endtime = strtotime('14:00');

for ($i = DBS_Global::$starttime; $i < DBS_Global::$endtime; $i += DBS_Global::$blocktime + DBS_Global::$buffertime) {
    $time = date('H:i', $i);
    array_push(DBS_Global::$valid_times, $time);
}
