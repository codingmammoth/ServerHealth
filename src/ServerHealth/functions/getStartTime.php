<?php

function getStartTime(): int|float
{
    $starttime = explode(' ', microtime());  
    return $starttime[1] + $starttime[0];
}
