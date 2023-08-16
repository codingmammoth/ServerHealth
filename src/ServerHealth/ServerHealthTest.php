<?php

require_once __DIR__."/ServerHealthResult.php";
require_once __DIR__."/ServerStates.php";

class ServerHealthTest
{
    protected $config = [];
    protected $db = false;
    protected $name = 'Server health test';

    protected function performTests() {}

    public function run()
    {
        try {
            return $this->performTests();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $result = new ServerHealthResult(
                $this->name,
                ServerStates::error,
                "Test failed. Error message: $error"
            );
            return $result->getResult();
        }
    }

    public function __construct(array $config = [], $db = null)
    {
        $this->config = $config;
        $this->db = $db;
    }
}
