<?php

function getLoads(): array
{
    $loads = sys_getloadavg();

    if ($loads === false) {
        return false;
    } else {
        return $loads;
    }
}
