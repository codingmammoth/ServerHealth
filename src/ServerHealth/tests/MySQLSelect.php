<?php

require_once __DIR__ . "/MySQLTest.php";

class MySQLSelect extends MySQLTest
{
    protected string $name = 'MySQL Select';

    public function run($db)
    {
        set_time_limit(0);
        $starttime = getStartTime();

        try {
            $result = mysqli_select_db($db, $this->config['db_name']);
            if (!$result) {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to select database " . $this->config['db_name']
                );
            } else {
                $totaltime = getRunningTime($starttime);

                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::ok,
                    "Total time: $totaltime",
                    $totaltime
                );
            }
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $this->result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Failed to select databases. Error: $error"
            );
        }
    }
}
