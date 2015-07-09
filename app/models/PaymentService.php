<?php

namespace app\models;


abstract class PaymentService extends Model
{

	private $errors;
	/**
	 * @param string $modelName - PaymentService model name
	 * @param \Silex\Application $app
	 * @return \app\models\PaymentService
	 * @throws \Exception
	 */
	public static function getModel($modelName, $app)
	{
		$names = [
			"one" =>  "\\" . __NAMESPACE__ . "\\PaymentService\\One",
			"two" =>  "\\" . __NAMESPACE__ . "\\PaymentService\\Two"
		];


		if(isset($names[$modelName])) {
			return new $names[$modelName]($app);
		} else {
			throw new \Exception("Payment service model `" . $modelName."` not found");
		}
	}

	abstract public function processRequest($data);

	protected function pushError($error)
	{
		$this->errors[] = $error;
	}
	protected function getErrors()
	{
		return $this->errors;
	}

	protected function findUser($userID)
	{
		return $this->getUserModel()->getUserByID($userID);
	}

	/**
	 * Получить модель для работы с пользователями
	 * @return \app\models\User
	 */
	protected function getUserModel()
	{
		$app = $this->getApp();
		return $app['models']('user');
	}

	protected function findPayment($paymentID)
	{
		return $this->getTransactionModel()->getTransactionByID($paymentID);
	}

	protected function saveIncome($userID, $paymentID, $amount)
	{
		return $this->getTransactionModel()->addTransaction([
			"user_id" => $userID,
			"amount" => $amount
		]);

	}

	/**
	 * Получить модель для работы с пользователями
	 * @return \app\models\Transaction
	 */
	protected function getTransactionModel()
	{
		$app = $this->getApp();
		return $app['models']('transaction');
	}
}

class PaymentServiceNotFoundException extends \Exception {

}