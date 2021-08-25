<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'mysqli/mysqli-connect.php';

date_default_timezone_set('Europe/Moscow');

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; // укажите здесь ваше имя
define('MINUTE', 60);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('FIVEWEEKS', 5 * WEEK);

$cardsOnPageAll = isset($_GET['cardsOnPageAll']);
$type_id = filter_input(INPUT_GET,'type_id');
$con = masqliConnect();
$types = getPostTypes($con);
$posts = getPosts($type_id, $cardsOnPageAll, $con);

$pageContent = include_template('main.php', ['posts' => $posts, 'types' => $types, 'type_id' => $type_id, 'cardsOnPageAll' => $cardsOnPageAll]);

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: популярное']);

print($layoutContent);