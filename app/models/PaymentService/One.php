<?php

namespace app\models\PaymentService;


class One  extends \app\models\PaymentService
{
	private $secret = 'some_secret';

	public function processRequest($data)
	{
		$this->getLogger()->addInfo("'One':new request with:" . json_encode($data));
		if($this->isValidData($data)) {
			// search for user
			$this->getLogger()->addInfo("'One':valid request");
			$user = $this->findUser($data['a']);
			if($user) {
				$tid = $this->saveIncome($user['id'], null, $data['b']);
				$this->getLogger()->addInfo("'One':transaction-saved:$tid");
				return $this->success();
			} else {
				$this->getLogger()->addError("'One':user-not-found");
				return $this->error("No user found with id: ".$data['user_id']);
			}
		} else {
			$this->getLogger()->addError("'One':invalid data:".implode(", ", $this->getErrors()));
			return $this->error($this->getErrors()[0]);
		}
	}

	public function isValidData($data)
	{
		if(empty($data['a']) || !is_numeric($data['a'])) {
			$this->pushError("Invalid user_id (parameter `a`)");
		}
		if(empty($data['b']) || !is_numeric($data['b']) || $data['b'] < 0) {
			$this->pushError("Invalid amount (parameter `b`)");
		}
		if(empty($data['md5'])) {
			$this->pushError("No hash");
		} elseif(md5($data['a'] . $data['b'] . $this->secret) != $data['md5']) {
			$this->pushError("Invalid hash");
		}

		return $this->getErrors() == false;
	}


	private function error($message)
	{
		return '<answer error="'.htmlspecialchars($message).'">0</answer>';
	}

	private function success()
	{
		return "<answer>1</answer>";
	}

} 