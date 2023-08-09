<?php

require_once __DIR__."../../ServerHealthTest.php";
require_once __DIR__."../../functions/getLoads.php";

class ServerLoad extends ServerHealthTest {

	protected string $name = 'Load';

	protected function performTest(): void
	{   
        $loads = getLoads();

		$warning_threshold = isset($this->config['warning_threshold']) ? $this->config['warning_threshold'] : 35;
		$error_threshold = isset($this->config['error_threshold']) ? $this->config['error_threshold'] : 80;

        if ($loads === false) {
            $this->status = ServerStates::error;
            $this->description = "Couldn't get loads.";
        } else if (!isset($this->config['loads_to_check']) || count($this->config['loads_to_check']) === 0) {
          	$this->status = ServerStates::error;
           	$this->description = "No loads to check.";
		} else {
			$states = [];
            $descriptions = [];
            $selected_loads = [];

            foreach ($this->config['loads_to_check'] as $selected_load) {
                $load = false;
                if ($selected_load === 'current') {
                    $load = $loads[0];
                    $selected_loads[] = $loads[0];
                    $descriptions[] = "Current load: $load.";
                } else if ($selected_load === 'average_5_min') {
                    $load = $loads[1];
                    $selected_loads[] = $loads[1];
                    $descriptions[] = "Load average 5 min: $load.";
                } else if ($selected_load === 'average_15_min') {
                    $load = $loads[2];
                    $selected_loads[] = $loads[2];
                    $descriptions[] = "Load average 15 min: $load.";
                }

                if ($load > $error_threshold) {
                    $states[] = ServerStates::error;
                } else if ($load > $warning_threshold) {
                    $states[] = ServerStates::warning;
                } else {
                    $states[] = ServerStates::ok;
                }
            }

            $this->status = ServerStates::getHighestState($states);
            if ($this->status === false) {
                $this->status = ServerStates::error;
                $this->description = 'Could not determine the status';
            } else {
                $this->description = implode(' ', $descriptions);
                $this->value = max($selected_loads);
            }
        }
	}

}
