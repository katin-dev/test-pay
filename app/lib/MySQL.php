<?php

namespace app\lib;

class MySQL extends \PDO
{

	public function __construct($config)
	{
		parent::__construct("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['username'], $config['password']);
		$this->query("SET NAMES utf8");
	}

	public function fetchAll($sql, $bind = [])
	{
		$stmt = $this->prepare($sql);
		if($stmt->execute( (array) $bind)) {
			return $stmt->fetchAll(self::FETCH_ASSOC);
		} else {
			$err = $stmt->errorInfo();
			throw new MySQLException($err[2], $err[1]);
		}
	}

	public function fetchRow($sql, $bind = [])
	{
		$stmt = $this->prepare($sql);
		if($stmt->execute((array) $bind)) {
			return $stmt->fetch(self::FETCH_ASSOC);
		} else {
			$err = $stmt->errorInfo();
			throw new MySQLException($err[2], $err[1]);
		}
	}

	public function insert($tableName, $bind)
	{
		$fields = array_keys($bind);
		$placehildres = array_map(function ($item) {
			return ':' . $item;
		}, $fields);

		$sql = "INSERT INTO $tableName (".implode(",", $fields).") VALUES(".implode(",", $placehildres).")";
		$stmt = $this->prepare($sql);

		if($stmt->execute($bind)) {
			return $this->lastInsertId();
		} else {
			$err = $stmt->errorInfo();
			throw new MySQLException($err[2], $err[1]);
		}
	}
}

class MySQLException extends \Exception {

}