<?php

namespace app\models;

class User extends Model
{
	public function findUsersByName($name)
	{
		return $this->getDb()->fetchAll("SELECT id as value, CONCAT(name, ' (', balance, ')') as label from `user` WHERE `name` LIKE ?", $name . '%');
	}

	public function getUserByID($id)
	{
		return $this->getDb()->fetchRow("SELECT * FROM user WHERE id = ?", $id);
	}

	public function getUserByName($name)
	{
		return $this->getDb()->fetchRow("SELECT * FROM user WHERE name = ?", $name);
	}
}