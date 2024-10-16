<?php

namespace Semonto\ServerHealth;

use Semonto\ServerHealth\{
    ServerStates,
    ServerHealthResult,
    ServerHealthTest
};

require_once __DIR__ . "../../ServerHealthTest.php";

class MySQLMaXDBConnections extends ServerHealthTest
{
    protected $name = 'MySQL MaX DB Connections';

    protected function performTests()
    {
        $status = ServerStates::ok;
        $description = "";
        $value = null;

        $warning_percentage_threshold = isset($this->config['warning_percentage_threshold']) ? $this->config['warning_percentage_threshold'] :75;
        $error_percentage_threshold = isset($this->config['error_percentage_threshold']) ? $this->config['error_percentage_threshold'] : 90;

        if ($warning_percentage_threshold >= $error_percentage_threshold) {
            $status = ServerStates::error;
            $description = "The warning percentage is bigger than the error percentage";
        } else if ($this->db === false) {
            $status = ServerStates::error;
            $description = "Failed to connect or login on the db server.";
        } else {
            try {
                $max_connections = false;
                if ($this->db->real_query("SHOW VARIABLES LIKE 'max_connections'")) {
                    if ($result = $this->db->use_result()) {
                        if ($row = $result->fetch_assoc()) {
                            if (isset($row['Value'])) {
                                $max_connections = (int) $row['Value'];
                            }
                        }
                        $result->close();
                    }
                }

                $current_connections = false;
                if ($this->db->real_query("SHOW STATUS LIKE 'Threads_connected'")) {
                    if ($result = $this->db->use_result()) {
                        if ($row = $result->fetch_assoc()) {
                            if (isset($row['Value'])) {
                                $current_connections = (int) $row['Value'];
                            }
                        }
                        $result->close();
                    }
                }

                if ($max_connections !== false && (int) $max_connections >= 0 && $current_connections !== false && (int) $current_connections >= 0) {
                    $percentage_connections = number_format((($current_connections / $max_connections) * 100), 2, ".", "");
                    $value = $current_connections;
                    $description = "Number of connections: $current_connections ($percentage_connections%)";

                    if ($percentage_connections >= $warning_percentage_threshold) {
                        $status =  $status = ServerStates::warning;
                    } else if ($percentage_connections >= $error_percentage_threshold) {
                        $status =  $status = ServerStates::error;
                    }
                } else {
                    $status = ServerStates::error;
                    $description = "Failed to get the number of active connections.";
                }
            } catch (\Throwable $th) {
                $status = ServerStates::error;
                $description = "Error: " . $th->getMessage();
            }
        }

        return new ServerHealthResult($this->name, $status, $description, $value);
    }
}
