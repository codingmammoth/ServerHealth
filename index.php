<?php

require_once __DIR__."/config/config.php";

require_once __DIR__."/src/ServerHealth/functions/functions.php";

require_once __DIR__."/src/ServerHealth/ServerHealth.php";
require_once __DIR__."/src/ServerHealth/tests/ServerLoad.php";
require_once __DIR__."/src/ServerHealth/tests/MySQLHealth.php";
require_once __DIR__."/src/ServerHealth/tests/MySQLPing.php";
require_once __DIR__."/src/ServerHealth/tests/MySQLSelect.php";
require_once __DIR__."/src/ServerHealth/tests/MySQLSelectTable.php";

error_reporting($error_level);

$health = new ServerHealth();
$health->tests([
    new ServerLoad(['warning_threshold' => 35, 'error_threshold' => 80, 'tests' => ['current', 'average_5_min', 'average_15_min']]),
    new MySQLHealth(['db_config' => $db_config, 'tests' => [
        new MySQLPing($db_config,),
        new MySQLSelect($db_config),
        new MySQLSelectTable($db_config)
    ]])
]);
$health->run();
$health->getResults();
