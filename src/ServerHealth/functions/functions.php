<?php

function getStartTime()
{
    $starttime = explode(' ', microtime());  
    return $starttime[1] + $starttime[0];
}

function getRunningTime($starttime, $round = 5)
{
    $mtime = explode(' ', microtime());  
    $totaltime = $mtime[0] +  $mtime[1] - $starttime;
    return round($totaltime, $round);
}

function connectToDB($config)
{
    try {
        $db = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], null, $config['db_port']);

        if ($db->connect_errno) {
            return false;
        } else {
            return $db;
        }
    } catch (\Throwable $th) {
        return false;
    }
}
