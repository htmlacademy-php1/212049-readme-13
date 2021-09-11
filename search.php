<?php
session_start();

if (!$_SESSION['user']) {
	header('Location: index.php', true, 302);
	die;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require_once 'helpers.php';
	require_once 'functions.php';
	define('MINUTE', 60);
	define('HOUR', 60 * MINUTE);
	define('DAY', 24 * HOUR);
	define('WEEK', 7 * DAY);
	define('FIVEWEEKS', 5 * WEEK);
	$blocksContent = [];
	$posts = [];

	$searchQuery = $_GET['search'] ?? '';

	if ($searchQuery) {
		$con = mysqliConnect();
		$posts = searchPosts($con, $searchQuery);
	}
}

if (!$posts && isset($_GET['search'])) {
	$mainContent = include_template('search-no-results.php', ['searchQuery' =>$searchQuery]);
	$layoutContent = include_template('layout.php', ['content' => $mainContent, 'user' => $_SESSION['user'], 'title' => 'readme: страница результатов поиска']);
	print($layoutContent);
	die;
}

foreach ($posts as $post) {
	$type = $post['type'];
	$path = 'search-' . $type . '.php';
	$blocksContent[] = include_template($path, ['post' => $post]);
}

$mainContent = include_template('search-main.php', ['posts' => $blocksContent, 'searchQuery' =>$searchQuery]);
$layoutContent = include_template('layout.php', ['content' => $mainContent, 'user' => $_SESSION['user'], 'title' => 'readme: страница результатов поиска']);
print($layoutContent);