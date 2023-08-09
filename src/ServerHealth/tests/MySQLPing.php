<?php

require_once __DIR__."../../ServerHealthTest.php";
require_once __DIR__."../../functions/getStartTime.php";
require_once __DIR__."../../functions/getRunningTime.php";

class MySQLPing extends ServerHealthTest {

	protected string $name = 'MySQL Ping';

	protected function performTest(): void
	{
		set_time_limit(0);
		$starttime = getStartTime();

		$conn = mysqli_connect($this->config['host'], $this->config['dbuser'], $this->config['dbpass'], $this->config['database'], $this->config['dbport']);
		if (!mysqli_ping($conn)) {
			$this->status = ServerStates::error;
			$this->description = "Failed to ping, connect or login on the db-server";
		} else {
			$this->status = ServerStates::ok;
			$totaltime = getRunningTime($starttime);
			$this->value = $totaltime;
			$this->description = "Total time: $totaltime";
		}
	}

}
