<?php
session_start();

if (!isset($_SESSION['user'])) {
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

if (!isset($_GET['post_id'])) {
	http_response_code(404);
	exit();
}

$postId = (int) $_GET['post_id'] ?? 0;
$userId = $_SESSION['user']['id'] ?? 0;
$required = ['text'];
$rules = [
	'text' => function() {
		return checkTextLength('text');
	}
];
$errors = [];
$con = mysqliConnect();
$post = getPost($con, $postId);
$comments = getComments($con, $postId);
$userId = $_SESSION['user']['id'] ?? 0;
$subUserId = $post['user_id'] ?? 0;
$isSubscribed = getSubscriptionInfo($con, $userId, $subUserId);
$tags = getTagsFromDB($con, $postId);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$comment = trim($_POST['text']) ?? '';

	if(getPostExistsInfo($con, $postId)){
		$errors = validateForm($rules, $required);

		if (empty($errors)) {
			addComment($con, $comment, $userId, $postId);
		}
	}
}

$pathBlock = 'post-' . $post['post_type'] . '.php';
$blockContent = include_template($pathBlock, ['post' => $post]);
$pageContent = include_template('post-main.php', ['blockContent' => $blockContent, 'post' => $post, 'tags' => $tags, 'user' => $_SESSION['user'], 'errors' => $errors, 'isSubscribed' => $isSubscribed, 'comments' => $comments]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'user' => $_SESSION['user'], 'title' => 'readme: публикация']);
print($layoutContent);
