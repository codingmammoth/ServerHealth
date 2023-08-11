<?php

require_once __DIR__ . "/MySQLTest.php";

class MySQLPing extends ServerHealthTest
{
    protected $name = 'MySQL Ping';

    public function performTests()
    {
        set_time_limit(0);
        $starttime = getStartTime();

        try {
            if ($this->config['db'] === false) {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to ping, connect or login on the db-server."
                );
            } else if (mysqli_ping($this->config['db'])) {
                $totaltime = getRunningTime($starttime);

                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::ok,
                    "Total time: $totaltime",
                    $totaltime
                );
            } else {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to ping, connect or login on the db-server"
                );
            }
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $this->result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Failed to ping, connect or login on the db-server. Error: $error"
            );
        }
    }
}
