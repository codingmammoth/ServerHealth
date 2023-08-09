<?php

require_once __DIR__ . "../../ServerHealthTest.php";

class MySQLHealth extends ServerHealthTest
{
    protected string $name = 'MySQL Health';
    protected $db = false;

    protected function connectToDB($config)
    {
        $starttime = getStartTime();

        try {
            $db = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], $config['db_port']);

            if ($db->connect_errno) {
                $result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to connect or login on the db-server"
                );
                $this->results[] = $result;

                return false;
            } else {
                $totaltime = getRunningTime($starttime);

                $result = new ServerHealthResult(
                    $this->name,
                    ServerStates::ok,
                    "Total time: $totaltime",
                    $totaltime
                );
                $this->results[] = $result;

                return $db;
            }
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Failed to ping, connect or login on the db-server. Error: $error"
            );
            $this->results[] = $result;

            return false;
        }
    }

    protected function performTests(): void
    {
        set_time_limit(0);

        if (isset($this->config['db_config'])) {
            $this->db = $this->connectToDB($this->config['db_config']);
        } else {
            $result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Database configuration is missing."
            );
            $this->results[] = $result;
            return;
        }

        if ($this->db && isset($this->config['tests']) && count($this->config['tests']) > 0) {
            foreach ($this->config['tests'] as $DBTest) {
                $DBTest->run($this->db);
                $this->results[] = $DBTest->getResult();
            }
        }
    }
}
