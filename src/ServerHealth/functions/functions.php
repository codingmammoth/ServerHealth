<?php

function getStartTime(): int|float
{
    $starttime = explode(' ', microtime());  
    return $starttime[1] + $starttime[0];
}

function getRunningTime($starttime, $round = 5): int|float
{
    $mtime = explode(' ', microtime());  
    $totaltime = $mtime[0] +  $mtime[1] - $starttime;
    return round($totaltime, $round);
}
