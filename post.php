<?php
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_GET['post_id'])) {
	http_response_code(404);
	exit();
}

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; 
$post_id = (int) $_GET['post_id'];

$con = masqliConnect();
$post = getPost($post_id, $con);

$pathBlock = 'post-' . $post['post_type'] . '.php';
$blockContent = include_template($pathBlock, ['post' => $post]);
$pageContent = include_template('post-main.php', ['blockContent' => $blockContent, 'post' => $post]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: публикация']);
print($layoutContent);
