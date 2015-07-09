<?php

namespace app\models;

class Transaction extends Model
{
	public function findTransactionsByUserID($userID)
	{
		return $this->getDb()->fetchAll("SELECT * FROM user_transaction WHERE user_id = ? ORDER BY datetime DESC", $userID);
	}

	public function getTransactionByID($id)
	{
		return $this->getDb()->fetchRow("SELECT * FROM user_transaction WHERE id = ?", $id);
	}

	public function addTransaction($data)
	{
		if(isset($data['user_id']) && is_numeric($data['user_id'])&& isset($data['amount']) && is_numeric($data['amount'])) {
			$data['datetime'] = date('Y-m-d H:i:s');
			$this->getDb()->beginTransaction();
			$this->getDb()->insert("user_transaction", $data);
			$tid = $this->getDb()->lastInsertId();
			$this->getDb()->query("UPDATE user SET balance = balance + " . $data['amount']." WHERE id = " . $data['user_id']);
			$this->getDb()->commit();

			return $tid;
		}
	}
}