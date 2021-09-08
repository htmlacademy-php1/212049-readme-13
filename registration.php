<?php
require_once 'helpers.php';
require_once 'functions.php';

$filePath = '';
$keys = [
	'email' => 'ЭЛЕКТРОННАЯ ПОЧТА',
	'login' => 'ЛОГИН',
	'password' => 'ПАРОЛЬ',
	'password-repeat' => 'ПОВТОР ПАРОЛЯ',
];
$rules = [
	'email' => function() {
		return validateEmail('email', $_POST);
	},
	'login' => function() {},
	'password' => function() {},
	'password-repeat' => function() {},
];
$required = ['email', 'login', 'password', 'password-repeat'];
$errors = [];
$modErrors = [];
$password = $_POST['password'] ?? '';
$passwordRepeat = $_POST['password-repeat'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = validateRegForm($rules, $required);
	$con = mysqliConnect();

	if (!isset($errors['email'])) {
		$errors['email'] = checkEmail($con, $_POST['email']);
	}

	if ($password !== $passwordRepeat) {
		$errors['password'] = 'Пароли не совпадают';
	}

	if (!empty($_FILES['userpic-file']['name'])) {
		if($type = mime_content_type($_FILES['userpic-file']['tmp_name'])) {
			$ext = explode('/', $type)[1];
            $fileName = uniqid() . '.' . $ext;
            $path = __DIR__ . '/uploads/' . $fileName;
            $filePath = $fileName;

			moveLoadedFile($_FILES['userpic-file']['tmp_name'], $path);
		} else {
			$errors['file'] = $error;
		}
	}

	$errors = array_filter($errors);
	$modErrors = modifyErrors($errors, $keys);

	if (!$errors) {
		insertUsersDataToDatabase($con, $filePath, $_POST);
	}
}

$mainContent = include_template('registration-main.php', ['errors' => $errors, 'modErrors' => $modErrors]);
$layout = include_template('registration-layout.php', ['mainContent' => $mainContent]);
print($layout);
