<?php
require_once 'helpers.php';
require_once 'functions.php';

date_default_timezone_set('Europe/Moscow');

$isAuth = rand(0, 1);
$userName = 'Yuriy'; // укажите здесь ваше имя
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

$pageContent = include_template('main.php', ['posts' => $posts, 'types' => $types, 'type_id' => $type_id, 'cardsOnPageAll' => $cardsOnPageAll]);

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'isAuth' => $isAuth, 'userName' => 'Yuriy', 'title' => 'readme: популярное']);

print($layoutContent);