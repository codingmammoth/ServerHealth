<?php

require_once __DIR__."../../ServerHealthTest.php";
require_once __DIR__."../../functions/getLoads.php";

class LoadNow extends ServerHealthTest {

	protected string $name = 'Load now';

	protected function performTest(): void
	{
		$load = getLoads(1);

		$warning_threshold = isset($this->config['warning_threshold']) ? $this->config['warning_threshold'] : 15;
		$error_threshold = isset($this->config['error_threshold']) ? $this->config['error_threshold'] : 80;

		if ($load === false) {
			$this->status = ServerStates::error;
			$this->description = "Couldn't get load.";
		} else if ($load > $error_threshold) {
			$this->status = ServerStates::error;
		} else if ($load > $warning_threshold) {
			$this->status = ServerStates::warning;
		} else {
			$this->status = ServerStates::ok;
		}
		
		if ($load !== false) {
			$this->description = "Load avarage last minute: $load";
			$this->value = $load;
		}
	}

}
