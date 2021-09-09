<?php
session_start();

if (!$_SESSION['user']) {
	header('Location: index.php', true, 302);
	die;
}

require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_GET['post_id'])) {
	http_response_code(404);
	exit();
}

$postId = (int) $_GET['post_id'];

$con = mysqliConnect();
$post = getPost($postId, $con);

$pathBlock = 'post-' . $post['post_type'] . '.php';
$blockContent = include_template($pathBlock, ['post' => $post]);
$pageContent = include_template('post-main.php', ['blockContent' => $blockContent, 'post' => $post]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'user' => $_SESSION, 'title' => 'readme: публикация']);
print($layoutContent);
