<?php

class MySQLPing extends ServerHealthTest
{
    protected $name = 'MySQL Ping';

    public function performTests()
    {
        $starttime = getStartTime();

        try {
            if ($this->db === false) {
                $result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to connect or login on the db server.",
                    getRunningTime($starttime)
                );
                return $result->getResult();
            } else if (mysqli_ping($this->db)) {
                $totaltime = getRunningTime($starttime);

                $result = new ServerHealthResult(
                    $this->name,
                    ServerStates::ok,
                    "Total time: $totaltime",
                    $totaltime
                );
                return $result->getResult();
            } else {
                $result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to ping the db server",
                    getRunningTime($starttime)
                );
                return $result->getResult();
            }
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Failed to ping, connect or login on the db server. Error: $error",
                getRunningTime($starttime)
            );
            return $result->getResult();
        }
    }
}
