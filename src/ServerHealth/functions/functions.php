<?php

function getStartTime()
{
    $starttime = explode(' ', microtime());  
    return $starttime[1] + $starttime[0];
}

function getRunningTime($starttime, $round = 5)
{
    $mtime = explode(' ', microtime());  
    $totaltime = $mtime[0] +  $mtime[1] - $starttime;
    return round($totaltime, $round);
}

function getTests ($config, $db) {
    try {
        $tests = [];

        $include_path_default_tests = '/../tests/';
        $include_path_custom_tests = '/../customtests/';

        foreach ($config['tests'] as $test) {
            $test_class = $test['test'];
            $test_config = $test['config'];

            $include_path = false;
            if (file_exists(__DIR__ . $include_path_custom_tests . $test_class . '.php')) {
                $include_path = __DIR__ . $include_path_custom_tests . $test_class . '.php';
            } else if (file_exists(__DIR__ . $include_path_default_tests . $test_class . '.php')) {
                $include_path = __DIR__ . $include_path_default_tests . $test_class . '.php';
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                exit("Test not found ($test_class)");
            }

            require_once $include_path;
            $tests[] = new $test_class($test_config, $db);
        }

        return $tests;
    } catch (\Throwable $th) {
        header('HTTP/1.1 500 Internal Server Error');
        exit("Error when getting the tests. Error: " . $th->getMessage());
    }
}

function connectToDB($config)
{
    try {
        $db = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], null, $config['db_port']);

        if ($db->connect_errno) {
            return false;
        } else {
            return $db;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function validateSecretKey($config)
{
    if (isset($config['secret_key']) && $config['secret_key'] !== '') {
        if (isset($_SERVER['HTTP_HEALTH_MONITOR_ACCESS_KEY'])) {
            return strcmp($config['secret_key'], $_SERVER['HTTP_HEALTH_MONITOR_ACCESS_KEY']) === 0;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function getCacheFilePath($cache_location, $installed_directory)
{
    return $cache_location . '/server_health_' . md5($installed_directory) . '.json';
}

function cacheResults($cache_file_path, $results)
{
    try {
        $cache = [
            'time' => time(),
            'results' => $results
        ];
        $json = json_encode($cache);
        $fh = fopen($cache_file_path, 'w');
        if ($fh) {
            fwrite($fh, $json);
            fclose($fh);
        }
    } catch (\Throwable $th) {
        // Couldn't store the results in the cache, continue without storing the results.
    }
}

function getCachedResults($cache_file_path, $cache_life_span)
{
    $results = false;
    try {
        if (file_exists($cache_file_path)) {
            $cached_data = file_get_contents($cache_file_path);
            if ($cached_data) {
                $cached_data = json_decode($cached_data, true);

                if (isset($cached_data['results']['status']) && time() - $cached_data['time'] <= $cache_life_span) {
                    $results = $cached_data['results'];
                }
            }
        }
    } catch (\Throwable $th) {
        // Failed to get the cached results, continue without the cached results.
    }

    return $results;
}
