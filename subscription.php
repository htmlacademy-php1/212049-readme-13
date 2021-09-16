<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: index.php', true, 302);
}

require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$con = mysqliConnect();
	$isUserExsists = getUserExistsInfo($con, $_GET['subUserId']);

	if ($isUserExsists) {
		if (!$_GET['isSubscribed']) {
			subscribe($con, $_SESSION['user']['id'], $_GET['subUserId']);
			header('Location: profile.php?user_id=' . $_GET['subUserId'], true, 302);
		} else {
			unsubscribe($con, $_SESSION['user']['id'], $_GET['subUserId']);
			header('Location: profile.php?user_id=' . $_GET['subUserId'], true, 302);
		}
	}
}