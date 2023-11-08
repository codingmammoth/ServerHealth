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

$installed_directory = __DIR__;
$cache_location = $config['cache_location'];
$cache_life_span = $config['cache_life_span'];
$cache_file_path = getCacheFilePath($cache_location, $installed_directory);

$results = getCachedResults($cache_file_path, $cache_life_span);

if (!$results) {
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

    cacheResults($cache_file_path, $results);
}

if ($results['status'] !== ServerStates::ok) { http_response_code(500); }

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);
