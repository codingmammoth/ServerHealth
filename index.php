<?php

require_once __DIR__."/config/config.php";

require_once __DIR__."/src/ServerHealth/functions/functions.php";

require_once __DIR__."/src/ServerHealth/ServerHealth.php";
require_once __DIR__."/src/ServerHealth/tests/ServerLoad.php";
require_once __DIR__."/src/ServerHealth/tests/MySQLPing.php";
require_once __DIR__."/src/ServerHealth/tests/MySQLSelect.php";
require_once __DIR__."/src/ServerHealth/tests/DiskSpace.php";

error_reporting(0);

$db = false;
$dbConfig = getDBConfig();
if ($dbConfig['should_connect']) {
    $db = connectToDB($dbConfig);
}

$health = new ServerHealth();
$health->tests([
    new ServerLoad([ 'type' => 'current', 'warning_threshold' => 5, 'error_threshold' => 10 ]),
    new ServerLoad([ 'type' => 'average_5_min', 'warning_threshold' => 2.5, 'error_threshold' => 5 ]),
    new ServerLoad([ 'type' => 'average_15_min', 'warning_threshold' => 0.5, 'error_threshold' => 1 ]),
    new MySQLPing([], $db),
    new MySQLSelect([ 'database' => 'example_database', 'database_table' => 'todo_list' ], $db),
    new DiskSpace([ 'disks' => [
        ['name' => '/dev/sda1', 'warning_percentage_threshold' => 50, 'error_percentage_threshold' => 75],
        ['name' => '/dev/sda2', 'warning_percentage_threshold' => 50, 'error_percentage_threshold' => 75],
    ]])
]);
$results = $health->run();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);
