<?php

$error_level = E_ALL;

$db_host  = ''; // Database-hostname (default: localhost)
$db_user  = 'sammy'; // Database-username
$db_pass  = 'password'; // Database-password
$db_name  = 'example_database'; // Database name (database to select)
$db_port  = 3306; // Default port number
$db_tables = ['todo_list']; // Database table (to perform a little `select` on)

$db_config = [
    'db_host'  => $db_host, 
    'db_user'  => $db_user, 
    'db_user'  => $db_user, 
    'db_pass'  => $db_pass, 
    'db_name'  => $db_name, 
    'db_port'  => $db_port,
    'db_tables' => $db_tables
];
