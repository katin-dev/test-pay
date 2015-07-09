<?php

use Silex\Application;
use \Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

// provider registration
$app->register(new Silex\Provider\TwigServiceProvider(), [
	'twig.path' => APP_DIR . '/views'
]);
$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => APP_DIR . '/var/log/app.log',
));

// database initialization
$app['db'] = new \app\lib\MySQL([
	"host" => 'localhost',
	"username" => "pay",
	"password" => "paypass",
	"dbname" => "pay"
]);

// app models loader
$app['models'] = $app->protect(function ($modelName) use ($app) {
	$modelClassName = '\\app\\models\\' . ucfirst($modelName);
	$model = new $modelClassName($app);
	return $model;
});


// Roting init

// main page
$app->get('/', function (Application $app) {
	return $app['twig']->render("index.twig", [
		"name" => "Сергей"
	]);
});

// payment systems routes
$apiController = function (Request $request, $name) use ($app) {
	$model = \app\models\PaymentService::getModel($name, $app);
	return $model->processRequest($request->query->all());
};
$app->match('/api/ps/one/', $apiController)->value("name", "one");
$app->match('/api/ps/two/', $apiController)->value("name", "two");


// web route: find users by part of their name
$app->get('/user/find/', function (Application $app, Request $req) {
	$name = $req->query->get("term");
	if(mb_strlen($name) > 2) {
		$users = $app['models']('user')->findUsersByName($name);
	} else {
		$users = [];
	}
	return $app->json($users);
});

// web route: get user transactions
$app->get('/user/payments/', function (Application $app, Request $req) {

	// поиск пользователя
	$name = $req->query->get("name");
	$id = $req->query->get("id");

	if($name || $id) {
		/* @var $userModel \app\models\User */
		$userModel = $users = $app['models']('user');
		if($id) {
			$user = $userModel->getUserByID($id);
		} else {
			$user = $userModel->getUserByName($name);
		}

		if ($user) {
			$payments = $app['models']('Transaction')->findTransactionsByUserID($user['id']);
		} else {
			$payments = [];
		}

		return $app['twig']->render("payments.twig", [
			"user_id" => $user['id'],
			"payments" => $payments
		]);
	} else {
		$app->abort(404, "User not found");
	}
});


$app['debug'] = true;
$app->run();