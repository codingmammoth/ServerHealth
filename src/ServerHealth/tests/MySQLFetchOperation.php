<?php

class MySQLFetchOperation extends ServerHealthTest
{
    protected $name = 'MySQL Fetch Operation';

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
            }

            if (!isset($this->config['database']) || $this->config['database'] === '') {
                $result = new ServerHealthResult(
                    $this->name,
                    ServerStates::warning,
                    "No database to select.",
                    getRunningTime($starttime)
                );
                return $result->getResult();
            } else {
                $sql_result = mysqli_select_db($this->db, $this->config['database']);
                if (!$sql_result) {
                    $result = new ServerHealthResult(
                        $this->name,
                        ServerStates::error,
                        "Failed to select database " . $this->config['database'],
                        getRunningTime($starttime)
                    );
                    return $result->getResult();
                }
            }

            if (isset($this->config['database_table'])) {
                $sql  = "SELECT * FROM " . $this->config['database_table'] . " LIMIT 50";
                $sql_result = mysqli_query($this->db, $sql);
                if (!$sql_result) {
                    $result = new ServerHealthResult(
                        $this->name,
                        ServerStates::error,
                        "Failed to select table " . $this->config['database_table'],
                        getRunningTime($starttime)
                    );
                    return $result->getResult();
                }
            }

            $totaltime = getRunningTime($starttime);
            $result = new ServerHealthResult(
                $this->name,
                ServerStates::ok,
                "Total time: $totaltime",
                $totaltime
            );
            return $result->getResult();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Error: $error",
                getRunningTime($starttime)
            );
            return $result->getResult();
        }
    }
}
