<?php

require_once __DIR__."../../ServerHealthTest.php";

class ServerLoad extends ServerHealthTest {

	protected string $name = 'Server Load';

	protected function performTests(): void
	{   
        $loads = getLoads();

		$warning_threshold = isset($this->config['warning_threshold']) ? $this->config['warning_threshold'] : 35;
		$error_threshold = isset($this->config['error_threshold']) ? $this->config['error_threshold'] : 80;

        if ($loads === false) {
            $result = new ServerHealthResult($this->name, ServerStates::error, "Couldn't get loads of server");
            $this->results[] = $result;
        } else if (!isset($this->config['tests']) || count($this->config['tests']) === 0) {
            $result = new ServerHealthResult($this->name, ServerStates::error, "No loads to check.");
            $this->results[] = $result;
		} else {
            foreach ($this->config['tests'] as $selected_load) {
                $load = false;
                $description = null;
                $state = false;
                $name = '';

                if ($selected_load === 'current') {
                    $load = $loads[0];
                    $description = "Current load: $load.";
                    $name = 'Current load';
                } else if ($selected_load === 'average_5_min') {
                    $load = $loads[1];
                    $description = "Load average 5 min: $load.";
                    $name = 'Load average 5 minutes';
                } else if ($selected_load === 'average_15_min') {
                    $load = $loads[2];
                    $description = "Load average 15 min: $load.";
                    $name = 'Load average 15 minutes';
                } else {
                    $load = false;
                    $description = "Unsupported load (Only current, average_5_min and average_15_min are supported)";
                    $name = $this->name . " ($selected_load)";
                }

                if ($load !== false) {
                    if ($load > $error_threshold) {
                       $state = ServerStates::error;
                   } else if ($load > $warning_threshold) {
                       $state = ServerStates::warning;
                   } else {
                       $state = ServerStates::ok;
                   }
                } else {
                    $state = ServerStates::error;
                    $load = false;
                }

                $result = new ServerHealthResult($name, $state, $description, $load);

                $this->results[] = $result;
            }
        }
	}

}
