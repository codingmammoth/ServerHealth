<?php

require_once __DIR__ . "/MySQLTest.php";

class MySQLSelectTable extends MySQLTest
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
                return;
            }


            $failed_table_names = [];
            foreach ($this->config['db_tables'] as $db_table) {
                $sql  = "SELECT * FROM $db_table LIMIT 50";
                $result = mysqli_query($db, $sql);

                if (!$result) {
                    $failed_table_names[] = $db_table;
                }
            }

            $totaltime = getRunningTime($starttime);

            if (count($failed_table_names) > 0) {
                $this->result = new ServerHealthResult(
                    $this->name,
                    ServerStates::error,
                    "Failed to select on tables (" . implode(', ', $failed_table_names) . ")"
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
                "Failed to select on tables. Error: $error"
            );
        }
    }
}
