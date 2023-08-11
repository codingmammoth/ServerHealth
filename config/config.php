<?php

function getDBConfig() {
    return [
        'should_connect' => true,
        'db_host' => '', // Database-hostname (default: localhost)
        'db_user' =>'sammy', // Database-username
        'db_pass' => 'password', // Database-password
        'db_port' => 3306, // Default port number
    ];
}
