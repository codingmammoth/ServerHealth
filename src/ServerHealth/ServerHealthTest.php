<?php

require_once __DIR__."/ServerHealthResult.php";
require_once __DIR__."/ServerStates.php";

class ServerHealthTest
{
    protected array $config = [];
    protected array $results = [];
    protected string $name = 'Server health test';

    protected function performTests(): void {}

    public function run(): void
    {
        try {
            $this->performTests();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $result = new ServerHealthResult(
                $this->name ? $this->name : $this::class, // Fallback to classname.
                ServerStates::error,
                "Test failed. Error message: $error"
            );
            $this->results[] = $result;
        }
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
}
