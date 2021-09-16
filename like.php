<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: index.php', true, 302);
}

require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$con = mysqliConnect();
	$postId = $_GET['postId'] ?? 0;
	$userId = $_SESSION['user']['id'] ?? 0;
	$path = basename($_SERVER['HTTP_REFERER']);
	$isPostExsists = getPostExistsInfo($con, $postId);
	$isPostLiked = getPostLikedInfo($con, $_SESSION['user']['id'], $postId);

	if ($isPostExsists) {
		if (!$isPostLiked) {
			like($con, $userId, $postId);
			header('Location: ' . $path, true, 302);
		} else {
			unlike($con, $userId, $postId);
			header('Location: ' . $path, true, 302);
		}
	}
}