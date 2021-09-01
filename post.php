<?php
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_GET['post_id'])) {
	http_response_code(404);
	exit();
}

$isAuth = rand(0, 1);
$userName = 'Yuriy'; 
$postId = (int) $_GET['post_id'];

$con = mysqliConnect();
$post = getPost($postId, $con);

$pathBlock = 'post-' . $post['post_type'] . '.php';
$blockContent = include_template($pathBlock, ['post' => $post]);
$pageContent = include_template('post-main.php', ['blockContent' => $blockContent, 'post' => $post]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'isAuth' => $isAuth, 'userName' => 'Yuriy', 'title' => 'readme: публикация']);
print($layoutContent);
