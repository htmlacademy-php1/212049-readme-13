<?php
session_start();

if (isset($_SESSION['user'])) {
	header('Location: feed.php', true, 302);
	die;
}

require_once 'helpers.php';
require_once 'functions.php';

$rules = [
	'login' => function() {
		return validateEmail('login', $_POST);
	},
	'password' => function() {},
];
$required = ['login', 'password'];
$errors = [];
$userId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = validateForm($rules, $required);
	$con = mysqliConnect();

	if (!$errors) {
		if (checkEmail($con, $_POST['login'])) {
			if ($userId = checkPassword($con, $_POST)) {
				$_SESSION['user'] = getUser($con, $userId);
				header('Location: feed.php', true, 302);
				die;
			} else {
				$errors['password'] = 'Неверный пароль';
			}
		} else {
			$errors['login'] = 'Вы ввели несуществующий логин';
		}
	}
}

$layout = include_template('main.php', ['errors' => $errors, 'title' => 'readme: блог, каким он должен быть']);
print($layout);