<?php

require_once __DIR__."/ServerHealthResult.php";
require_once __DIR__."/ServerStates.php";

class ServerHealthTest
{
    protected bool|string $status = false;
    protected string $name = '';
    protected string|null $description = '';
    protected int|float|null $value = null;
    protected array $config = [];

    protected function performTest(): void {}

    public function run(): void
    {
        try {
            $this->performTest();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $this->status = ServerStates::error;
            $this->description = "Test failed. Error message: $error";
            $this->value = null;
        }
    }

    public function getResult(): array
    {
        $result = new ServerHealthResult(
            $this->name ? $this->name : $this::class, // Fallback to classname.
            $this->status, 
            $this->description,
            $this->value
        );
        return $result->getResult();
    }

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
}
