<?php

require_once __DIR__."/config/config.php";

require_once __DIR__."/src/ServerHealth/functions/functions.php";
require_once __DIR__."/src/ServerHealth/ServerHealth.php";

error_reporting(0);

$config = getConfig();
$tests = getTests($config);
$health = new ServerHealth();
$health->tests($tests);
$results = $health->run();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);
