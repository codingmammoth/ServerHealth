<?php

require_once __DIR__ . "/MySQLTest.php";

class MySQLSelect extends MySQLTest
{
    protected string $name = 'MySQL Select';

    public function run($db)
    {
        set_time_limit(0);
        $starttime = getStartTime();

        $failed_db_names = [];

        try {
            foreach ($this->config['db_names'] as $db_name) {
                $result = mysqli_select_db($db, $db_name);
                if (!$result) {
                    $failed_db_names[] = $db_name;
                }
            }

            $totaltime = getRunningTime($starttime);

            if (count($failed_db_names) > 0) {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to select databases (" . implode(', ', $failed_db_names) . ")"
                );
            } else {
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