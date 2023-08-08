<?php

require_once __DIR__."../../ServerHealthTest.php";
require_once __DIR__."../../functions/getStartTime.php";
require_once __DIR__."../../functions/getRunningTime.php";

class PHPLus extends ServerHealthTest {

	protected string $name = 'PHP Lus';

	protected function performTest(): void
	{
		$starttime = getStartTime();

		$warning_threshold = isset($this->config['warning_threshold']) ? $this->config['warning_threshold'] : 0.5;
		$error_threshold = isset($this->config['error_threshold']) ? $this->config['error_threshold'] : 1.0;

		$o = 1; // simple lus
		for ($i = 0; $i < 5000; $i++) $o += $i; 
	
		$totaltime = getRunningTime($starttime);

		$this->status = ServerStates::ok;
		if ($totaltime > $warning_threshold ) $this->status = ServerStates::warning;
		else if ($totaltime > $error_threshold ) $this->status = ServerStates::error;
		$this->description = "Total time: $totaltime";
		$this->value = $totaltime;
	}

}
