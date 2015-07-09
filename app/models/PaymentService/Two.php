<?php

namespace app\models\PaymentService;


class Two  extends \app\models\PaymentService
{
	private $secret = 'some_secret';

	public function processRequest($data)
	{
		$this->getLogger()->addInfo("'Two':new request with:" . json_encode($data));
		if($this->isValidData($data)) {
			// search for user
			$this->getLogger()->addInfo("'Two':valid request");
			$user = $this->findUser($data['x']);
			if($user) {
				$tid = $this->saveIncome($user['id'], null, $data['y']);
				$this->getLogger()->addInfo("'Two':transaction-saved:$tid");
				return $this->success();
			} else {
				$this->getLogger()->addError("'Two':user-not-found");
				return $this->error("No user found with id: ".$data['user_id']);
			}
		} else {
			$this->getLogger()->addError("'Two':invalid data:".implode(", ", $this->getErrors()));
			return $this->error($this->getErrors()[0]);
		}
	}

	public function isValidData($data)
	{
		if(empty($data['x']) || !is_numeric($data['x'])) {
			$this->pushError("Invalid user_id (param `x`)");
		}
		if(empty($data['y']) || !is_numeric($data['y']) || $data['y'] < 0) {
			$this->pushError("Invalid sum (parameter `y`)");
		}
		if(empty($data['md5'])) {
			$this->pushError("Empty hash");
		} elseif(md5($data['x'] .  $data['y'] . $this->secret) != $data['md5']) {
			$this->pushError("Invalid hash");
		}

		return $this->getErrors() == false;
	}


	private function error($message)
	{
		return 'ERR';
	}

	private function success()
	{
		return "OK";
	}
} 