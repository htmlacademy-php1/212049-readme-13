<?php
session_start();

if (!$_SESSION['user']) {
	header('Location: index.php', true, 302);
	die;
}

require_once 'helpers.php';
require_once 'functions.php';

define('MINUTE', 60);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('FIVEWEEKS', 5 * WEEK);
$blockContents = [];
$searchQuery = $_GET['search'] ?? '';

$con = mysqliConnect();
$sub = getSubscriptions($con, $_SESSION['user']['id']);
$subPosts = getSubPosts($con, $sub);
$types = getPostTypes($con);
$cardsOnPageAll = isset($_GET['cardsOnPageAll']);
$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : 0;

foreach ($subPosts as $post) {
	if ($type_id === 0 || (int)$type_id === $post['content_type_id']) {
		$path = 'feed-' . $post['type'] . '.php';
		$blockContent = include_template($path, ['post' => $post]);
		$blockContents[] = $blockContent;
	}
	
}
$mainContent = include_template('feed-main.php', ['contents' => $blockContents, 'types' => $types, 'cardsOnPageAll' => $cardsOnPageAll, 'type_id' => $type_id]);
$layoutContent = include_template('feed-layout.php', ['mainContent' => $mainContent, 'user' => $_SESSION['user'], 'searchQuery' => $searchQuery, 'title' => 'readme: моя лента']);
print($layoutContent);