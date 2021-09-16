<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: index.php', true, 302);
}

require_once 'helpers.php';
require_once 'functions.php';

define('MINUTE', 60);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('FIVEWEEKS', 5 * WEEK);
$filters = ['posts' => 'Посты', 'likes' => 'Лайки', 'subscriptions' => 'Подписки'];
$activeFilter = $_GET['filter'] ?? 'posts';
$userId = $_SESSION['user']['id'] ?? 0;
$subUserId = $_GET['user_id'] ?? 0;
$con = mysqliConnect();
$isSubscribed = getSubscriptionInfo($con, $userId, $subUserId);
$blocksContent = [];

if ($activeFilter === 'posts') {
	$posts = getProfilePosts($con, $subUserId);
	$profileUser = getProfileUser($con, $subUserId);

	foreach ($posts as $post) {
		$path = 'profile-' . $activeFilter . '-' . $post['class_name'] .'.php';
		$blocksContent[] = include_template($path, ['post' => $post, 'profileUser' => $profileUser, 'user' => $_SESSION['user']]);
	}
}
$blocksContainer = include_template('profile-posts.php', ['blocksContent' => $blocksContent]);
$mainContent = include_template('profile-main.php', ['blocksContainer' => $blocksContainer, 'filters' => $filters, 'activeFilter' => $activeFilter, 'profileUser' => $profileUser, 'isSubscribed' => $isSubscribed]);
$layoutContent = include_template('layout.php', ['content' => $mainContent, 'user' => $_SESSION['user']]);
print($layoutContent); 
