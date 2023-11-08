<?php

function getConfig() {

    $cache_location = sys_get_temp_dir(); // The directory to store the cache.
    $cache_life_span = 45; // The maximum life span for the cached results in seconds.

    $config = [
        'secret_key' => '', // The secret key shown in the settings for this server in Semonto.
        'cache_location' => $cache_location,
        'cache_life_span' => $cache_life_span,
        'db' => [
            'connect' => true,
            'db_host' => '', // Database-hostname (default: localhost)
            'db_user' =>'sammy', // Database-username
            'db_pass' => 'password', // Database-password
            'db_port' => 3306, // Default port number
        ],
        'tests' => [
            [
                'test' => 'ServerLoad',
                'config' => [ 'type' => 'current', 'warning_threshold' => 5, 'error_threshold' => 15 ]
            ], 
            [
                'test' => 'ServerLoad',
                'config' => [ 'type' => 'average_5_min', 'warning_threshold' => 5, 'error_threshold' => 15 ]
        
            ],
            [
                'test' => 'ServerLoad',
                'config' => [ 'type' => 'average_15_min', 'warning_threshold' => 5, 'error_threshold' => 15 ]
            ], 
            [
                'test' => 'MySQLPing',
                'config' => []
            ], 
            [
                'test' => 'MySQLFetchOperation',
                'config' => [ 'database' => 'example_database', 'database_table' => 'todo_list' ]
            ],
            [
                'test' => 'DiskSpace',
                'config' => [ 
                    'disks' => [
                        ['name' => '/dev/sda1', 'warning_percentage_threshold' => 75, 'error_percentage_threshold' => 90],
                        ['name' => '/dev/sda2', 'warning_percentage_threshold' => 75, 'error_percentage_threshold' => 90],
                    ]
                ]
            ],
            [
                'test' => 'MemoryUsage',
                'config' => [ 'warning_percentage_threshold' => 90, 'error_percentage_threshold' => 95 ]
            ]
        ]
    ];

    return $config;
}
