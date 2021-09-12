<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: index.php', true, 302);
	die;
}

require_once 'helpers.php';
require_once 'functions.php';

date_default_timezone_set('Europe/Moscow');

define('MINUTE', 60);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('FIVEWEEKS', 5 * WEEK);

$cardsOnPageAll = isset($_GET['cardsOnPageAll']);
$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : 0;
$con = mysqliConnect();
$types = getPostTypes($con);
$posts = getPosts($type_id, $cardsOnPageAll, $con);

$pageContent = include_template('popular-main.php', ['posts' => $posts, 'types' => $types, 'type_id' => $type_id, 'cardsOnPageAll' => $cardsOnPageAll]);

$layoutContent = include_template('popular-layout.php', ['content' => $pageContent, 'user' => $_SESSION['user'], 'title' => 'readme: популярное']);
print($layoutContent);
