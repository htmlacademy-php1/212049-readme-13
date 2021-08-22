<?php
require_once 'helpers.php';

if (!isset($_GET['post_id'])) {
	header(' ', true, 404);
	exit();
}

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; 
$post_id = (int) $_GET['post_id'];

$con = mysqli_connect('localhost', 'root', '', 'readme');

if (!$con) {
	echo 'Ошибка соединения с сервером ' . mysqli_error($con);
}

mysqli_set_charset($con, 'utf8');

$query_post = 'SELECT posts.*, users.login AS author, users.avatar, content_types.class_name AS post_type,' .
				'(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) AS likes_count' . 
				' FROM posts' .
				' JOIN users ON posts.user_id = users.id' .
				' JOIN content_types ON posts.content_type_id = content_types.id' .
				' WHERE posts.id = ?;';

$stmt = mysqli_prepare($con, $query_post);
mysqli_stmt_bind_param($stmt, 'i', $post_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    die('Ошибка получения данных: ' . mysqli_error($con));
}

$post = mysqli_fetch_assoc($res);

if (empty($post)) {
	header(' ', true, 404);
	exit();
}

$pathBlock = 'post-' . $post['post_type'] . '.php';
$blockContent = include_template($pathBlock, ['post' => $post]);
$pageContent = include_template('post-main.php', ['blockContent' => $blockContent, 'post' => $post]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: публикация']);
print($layoutContent);
