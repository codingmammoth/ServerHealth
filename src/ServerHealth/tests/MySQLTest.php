<?php

class MySQLTest {

	protected string $name = 'MySQL Test';
	protected array $config = [];
	protected $result;

	public function getResult()
	{
		return $this->result;
	}

	public function run($db) {}

	public function __construct($config)
	{
		$this->config = $config;
	}
}
