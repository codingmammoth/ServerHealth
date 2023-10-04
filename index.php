<?php

require_once __DIR__."/config/config.php";

require_once __DIR__."/src/ServerHealth/functions/functions.php";
require_once __DIR__."/src/ServerHealth/ServerHealth.php";
require_once __DIR__."/src/ServerHealth/ServerStates.php";

error_reporting(0);

$config = getConfig();
if (!validateSecretKey($config)) {
    http_response_code(403);
    exit();
}
$tests = getTests($config);
$health = new ServerHealth();
$health->tests($tests);
$results = $health->run();

if ($results['status'] !== ServerStates::ok) { http_response_code(500); }

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);
