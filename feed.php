<?php
session_start();

if (!$_SESSION) {
	header('Location: index.php', true, 302);
	die;
}

require_once 'helpers.php';

$mainContent = include_template('feed-main.php');
$layoutContent = include_template('feed-layout.php', ['mainContent' => $mainContent, 'user' => $_SESSION]);
print($layoutContent);