<?php

require_once __DIR__."/config/config.php";

require_once __DIR__."/src/ServerHealth/functions/functions.php";
require_once __DIR__."/src/ServerHealth/ServerHealth.php";
require_once __DIR__."/src/ServerHealth/ServerStates.php";

error_reporting(0);

$config = getConfig();

$db = false;
if ($config['db']['connect']) {
    $db = connectToDB($config['db']);
}

$tests = getTests($config, $db);
$health = new ServerHealth();
$health->tests($tests);
$results = $health->run();

if ($db) {
    $db->close();
}

if ($results['status'] !== ServerStates::ok) { http_response_code(500); }

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);
