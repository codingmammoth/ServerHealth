<?php

require_once __DIR__ . "../../ServerHealthTest.php";

class ExampleCustomTest extends ServerHealthTest
{
    protected $name = 'Example Custom Test';

    protected function performTests()
    {
        $result = new ServerHealthResult($this->name, ServerStates::error, "This is an example custom test.");
        return $result->getResult();
    }
}
