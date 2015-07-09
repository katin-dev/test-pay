<?php

namespace app\models;

class Model
{
	private $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * @return \Silex\Application
	 */
	protected function getApp()
	{
		return $this->app;
	}

	/**
	 * @return \PDO
	 */
	protected function getDb()
	{
		return $this->app['db'];
	}

	/**
	 * @return \monolog\Logger
	 */
	protected function getLogger()
	{
		return $this->app['monolog'];
	}
}