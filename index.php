<?php

require_once __DIR__."/config/config.php";
require_once __DIR__."/src/ServerHealth/ServerHealth.php";
require_once __DIR__."/src/ServerHealth/tests/PHPLus.php";
require_once __DIR__."/src/ServerHealth/tests/LoadNow.php";

error_reporting($error_level);

$health = new ServerHealth();
$health->tests([
    new PHPLus(['warning_threshold' => 0.5, 'error_threshold' => 1.0]),
    new LoadNow(['warning_threshold' => 15, 'error_threshold' => 80])
]);
$health->run();
$health->getResults();
