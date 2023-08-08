<?php

function getLoads(int $minutes): float|false
{
    $loads = sys_getloadavg();

    if ($loads === false) {
        return false;
    } else if ($minutes === 1) {
        return $loads[0];
    } else if ($minutes === 5) {
        return $loads[1];
    } else if ($minutes === 15) {
        return $loads[2];
    } else {
        return false;
    }
}
