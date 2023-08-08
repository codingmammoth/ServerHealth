<?php

class ServerHealth
{
    private $tests = [];
    private $results = [];

    public function tests(array $tests): void
    {
        $this->tests = $tests;
    }

    public function run(): void
    {
        foreach ($this->tests as $test) {
            $test->run();
            $this->results[] = $test->getResult();
        }
    }

    public function getResults(): void
    {
        $result = [
            "status" => "TODO",
            "results" => $this->results,
            "version" => "TODO"
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
}
