<?php

class ServerHealth
{
    private $tests = [];
    private $results = [];

    public function tests(array $tests)
    {
        $this->tests = $tests;
    }

    public function run()
    {
        foreach ($this->tests as $test) {
            $result = $test->run();
            // var_dump($result);
            $this->results[] = $result->getResult();
        }
    }

    public function getResults()
    {
        $results = [
            "results" => $this->results,
        ];

        $test_states = array_map(function($result) {
            return $result['status'];
        }, $this->results);

        $results['status'] = ServerStates::getHighestState($test_states);

        return $results;
    }
}
