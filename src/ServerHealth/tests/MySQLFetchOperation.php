<?php

class MySQLFetchOperation extends ServerHealthTest
{
    protected $name = 'MySQL Fetch Operation';

    public function performTests()
    {
        $starttime = getStartTime();

        try {
            if ($this->db === false) {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to connect or login on the db server.",
                    getRunningTime($starttime)
                );
                return;
            }

            if (!isset($this->config['database']) || $this->config['database'] === '') {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::warning,
                    "No database to select.",
                    getRunningTime($starttime)
                );
                return;
            } else {
                $result = mysqli_select_db($this->db, $this->config['database']);
                if (!$result) {
                    $this->result = new ServerHealthResult(
                        $this->name,
                        ServerStates::error,
                        "Failed to select database " . $this->config['database'],
                        getRunningTime($starttime)
                    );
                    return;
                }
            }

            if (isset($this->config['database_table'])) {
                $sql  = "SELECT * FROM " . $this->config['database_table'] . " LIMIT 50";
                $result = mysqli_query($this->db, $sql);
                if (!$result) {
                    $this->result = new ServerHealthResult(
                        $this->name,
                        ServerStates::error,
                        "Failed to select table " . $this->config['database_table'],
                        getRunningTime($starttime)
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
                "Error: $error",
                getRunningTime($starttime)
            );
        }
    }
}
