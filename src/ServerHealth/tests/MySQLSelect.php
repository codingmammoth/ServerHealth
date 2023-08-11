<?php

class MySQLSelect extends ServerHealthTest
{
    protected $name = 'MySQL Select';

    public function performTests()
    {
        set_time_limit(0);
        $starttime = getStartTime();

        try {
            if ($this->config['db'] === false) {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to ping, connect or login on the db server."
                );
                return;
            }

            if (!isset($this->config['database']) || $this->config['database'] === '') {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "No database to select."
                );
                return;
            } else {
                $result = mysqli_select_db($this->config['db'], $this->config['database']);
                if (!$result) {
                    $this->result = new ServerHealthResult(
                        $this->name,
                        ServerStates::error,
                        "Failed to select database " . $this->config['database']
                    );
                    return;
                }
            }

            if (isset($this->config['database_table'])) {
                $sql  = "SELECT * FROM " . $this->config['database_table'] . " LIMIT 50";
                $result = mysqli_query($this->config['db'], $sql);
                if (!$result) {
                    $this->result = new ServerHealthResult(
                        $this->name,
                        ServerStates::error,
                        "Failed to select table " . $this->config['database_table']
                    );
                    return;
                }
            }

            $totaltime = getRunningTime($starttime);
            $this->result = new ServerHealthResult(
                $this->name,
                ServerStates::ok,
                "Total time: $totaltime",
                $totaltime
            );
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $this->result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Error: $error"
            );
        }
    }
}
